<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Question;
use App\Models\Test;
use App\Models\TestSection;
use App\Models\TestSectionQuestion;
use Illuminate\Support\Facades\DB;

class BuildTestFromBlueprintAction
{
    /**
     * Build test sections and populate snapshots from the Question Bank based on blueprint criteria.
     *
     * @param Test $test
     * @param array<int, array> $sectionsData
     * @return array{success: bool, message: string, total_selected: int}
     */
    public function execute(Test $test, array $sectionsData): array
    {
        return DB::transaction(function () use ($test, $sectionsData) {
            $totalSelected = 0;

            foreach ($sectionsData as $index => $data) {
                // 1. Create or update TestSection
                $section = TestSection::updateOrCreate(
                    [
                        'test_id' => $test->id,
                        'order' => $data['order'] ?? ($index + 1),
                    ],
                    [
                        'name' => $data['name'],
                        'duration_minutes' => (int) ($data['duration_minutes'] ?? 40),
                        'correct_marks' => (float) ($data['correct_marks'] ?? 3.0),
                        'negative_mcq_marks' => (float) ($data['negative_mcq_marks'] ?? 1.0),
                        'negative_tita_marks' => (float) ($data['negative_tita_marks'] ?? 0.0),
                        'is_section_locked' => (bool) ($data['is_section_locked'] ?? true),
                        'allow_return' => (bool) ($data['allow_return'] ?? false),
                        'instructions' => $data['instructions'] ?? null,
                    ]
                );

                // Clear previous snapshot questions if regenerating
                TestSectionQuestion::where('test_section_id', $section->id)->delete();

                // 2. Query Question Bank based on blueprint criteria
                $sectionCategory = $data['section_category'] ?? 'va';
                $targetCount = (int) ($data['questions_count'] ?? 10);
                $minDiff = (int) ($data['min_difficulty'] ?? 1);
                $maxDiff = (int) ($data['max_difficulty'] ?? 5);

                $query = Question::query()
                    ->where('section_category', $sectionCategory)
                    ->whereBetween('difficulty', [$minDiff, $maxDiff]);

                // Filter by topic if specified
                if (!empty($data['topic_ids'])) {
                    $query->whereHas('topics', function ($q) use ($data) {
                        $q->whereIn('topics.id', (array) $data['topic_ids']);
                    });
                }

                // If RC / Passage rule applies, order by passage_id so set questions stay grouped
                $questions = $query->orderByRaw('CASE WHEN passage_id IS NULL THEN 1 ELSE 0 END, passage_id ASC, id ASC')
                    ->take($targetCount)
                    ->get();

                // 3. Attach questions snapshot to section
                $order = 1;
                foreach ($questions as $q) {
                    TestSectionQuestion::create([
                        'test_section_id' => $section->id,
                        'question_id' => $q->id,
                        'order' => $order++,
                    ]);
                    $totalSelected++;
                }
            }

            // Recalculate total test duration
            $totalDuration = $test->sections()->sum('duration_minutes');
            $test->update(['total_duration_minutes' => $totalDuration]);

            return [
                'success' => true,
                'message' => "Blueprint engine successfully generated test paper with {$totalSelected} questions across " . count($sectionsData) . " sections.",
                'total_selected' => $totalSelected,
            ];
        });
    }
}
