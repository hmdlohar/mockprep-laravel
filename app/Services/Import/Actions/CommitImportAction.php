<?php

declare(strict_types=1);

namespace App\Services\Import\Actions;

use App\Models\Passage;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestSection;
use App\Models\TestSectionQuestion;
use App\Models\Topic;
use App\Services\Import\DTOs\ParsedImportBatchDTO;
use App\Services\Import\DTOs\TestGenerationConfigDTO;
use App\Services\Import\QuestionSetPicker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommitImportAction
{
    /**
     * Persist the approved questions from the batch into the Question Bank and optionally build a CBT Test.
     *
     * @return array{
     *   imported_questions: int,
     *   imported_passages: int,
     *   imported_topics: int,
     *   created_test_id: ?int,
     *   created_test_title: ?string,
     *   created_test_slug: ?string,
     *   test_questions_count: int
     * }
     */
    public function execute(ParsedImportBatchDTO $batch, ?TestGenerationConfigDTO $testConfig = null): array
    {
        return DB::transaction(function () use ($batch, $testConfig) {
            $importedQuestionsCount = 0;
            $importedPassagesCount = 0;
            $importedTopicsCount = 0;

            // 1. Process & Deduplicate Passages
            $persistedPassagesMap = []; // [externalId => dbPassageId]
            foreach ($batch->passages as $extPassageId => $passageText) {
                $passage = Passage::firstOrCreate(
                    ['content' => $passageText],
                    [
                        'section_category' => \App\Enums\SectionCategory::VA,
                    ]
                );
                $persistedPassagesMap[$extPassageId] = $passage->id;
                if ($passage->wasRecentlyCreated) {
                    $importedPassagesCount++;
                }
            }

            // 2. Process Topics
            $persistedTopicsMap = []; // [topicName => dbTopicId]
            foreach ($batch->topics as $topicName) {
                $slug = Str::slug($topicName);
                if (empty($slug)) continue;

                $topic = Topic::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $topicName,
                        'category' => \App\Enums\SectionCategory::QA,
                    ]
                );
                $persistedTopicsMap[$topicName] = $topic->id;
                if ($topic->wasRecentlyCreated) {
                    $importedTopicsCount++;
                }
            }

            // 3. Process Approved Questions & Group by Section Category
            $persistedQuestionsBySection = []; // [sectionValue => array<{dto: DTO, dbId: int}>]

            foreach ($batch->questions as $qDTO) {
                if (!$qDTO->selectedForImport) {
                    continue; // Skip unselected or excluded duplicates
                }

                $passageDbId = null;
                if ($qDTO->passageExternalId && isset($persistedPassagesMap[$qDTO->passageExternalId])) {
                    $passageDbId = $persistedPassagesMap[$qDTO->passageExternalId];
                }

                $question = Question::create([
                    'passage_id' => $passageDbId,
                    'section_category' => $qDTO->sectionCategory,
                    'type' => $qDTO->type,
                    'content' => $qDTO->content,
                    'options' => $qDTO->options,
                    'correct_answer' => $qDTO->correctAnswer,
                    'explanation' => $qDTO->explanation,
                    'difficulty' => $qDTO->difficulty,
                    'source' => $batch->sourceFileName,
                    'external_id' => $qDTO->externalId,
                ]);

                // Attach Topics
                $topicIdsToSync = [];
                foreach ($qDTO->topics as $tName) {
                    if (isset($persistedTopicsMap[$tName])) {
                        $topicIdsToSync[] = $persistedTopicsMap[$tName];
                    }
                }
                if (!empty($topicIdsToSync)) {
                    $question->topics()->sync($topicIdsToSync);
                }

                $persistedQuestionsBySection[$qDTO->sectionCategory->value][] = [
                    'dto' => $qDTO,
                    'dbId' => $question->id,
                ];

                $importedQuestionsCount++;
            }

            // 4. Optionally Generate CBT Mock Test with Unbroken Question Sets
            $createdTest = null;
            $totalAttachedToTest = 0;

            if ($testConfig && $testConfig->createTest) {
                $baseSlug = Str::slug($testConfig->testTitle ?: 'mock-test');
                $slug = $baseSlug;
                $counter = 1;
                while (Test::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-" . ($counter++);
                }

                $createdTest = Test::create([
                    'title' => $testConfig->testTitle ?: 'Imported CBT Mock Test',
                    'slug' => $slug,
                    'category' => $testConfig->examCategory,
                    'total_duration_minutes' => $testConfig->totalDurationMinutes,
                    'has_calculator' => $testConfig->hasCalculator,
                    'is_published' => $testConfig->isPublished,
                ]);

                $picker = new QuestionSetPicker();
                $canonicalOrder = ['va', 'dilr', 'qa'];
                $secOrder = 1;

                foreach ($canonicalOrder as $secCatValue) {
                    $sectionItems = $persistedQuestionsBySection[$secCatValue] ?? [];
                    if (empty($sectionItems)) {
                        continue;
                    }

                    $targetQuota = $testConfig->sectionQuotas[$secCatValue] ?? count($sectionItems);
                    $duration = $testConfig->sectionDurations[$secCatValue] ?? 40;

                    $secEnum = \App\Enums\SectionCategory::from($secCatValue);
                    $sectionName = match ($secEnum) {
                        \App\Enums\SectionCategory::VA => 'Verbal Ability & Reading Comprehension',
                        \App\Enums\SectionCategory::DILR => 'Data Interpretation & Logical Reasoning',
                        \App\Enums\SectionCategory::QA => 'Quantitative Aptitude',
                    };

                    $section = TestSection::create([
                        'test_id' => $createdTest->id,
                        'name' => $sectionName,
                        'order' => $secOrder++,
                        'duration_minutes' => $duration,
                        'correct_marks' => '3.00',
                        'negative_mcq_marks' => '1.00',
                        'negative_tita_marks' => '0.00',
                        'is_section_locked' => $testConfig->isSectionLocked,
                        'allow_return' => false,
                        'instructions' => 'Standard examination rules apply.',
                    ]);

                    // Pick unbroken question IDs preserving full passage sets
                    $chosenQuestionIds = $picker->pickUnbrokenQuestionIds($sectionItems, $targetQuota);

                    foreach ($chosenQuestionIds as $qOrder => $qId) {
                        TestSectionQuestion::create([
                            'test_section_id' => $section->id,
                            'question_id' => $qId,
                            'order' => $qOrder + 1,
                        ]);
                        $totalAttachedToTest++;
                    }
                }
            }

            return [
                'imported_questions' => $importedQuestionsCount,
                'imported_passages' => $importedPassagesCount,
                'imported_topics' => $importedTopicsCount,
                'created_test_id' => $createdTest?->id,
                'created_test_title' => $createdTest?->title,
                'created_test_slug' => $createdTest?->slug,
                'test_questions_count' => $totalAttachedToTest,
            ];
        });
    }
}
