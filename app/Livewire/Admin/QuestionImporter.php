<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Services\Import\Actions\CommitImportAction;
use App\Services\Import\DuplicateDetector;
use App\Services\Import\DTOs\ParsedImportBatchDTO;
use App\Services\Import\ImportParserManager;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class QuestionImporter extends Component
{
    use WithFileUploads;

    public $uploadedFile = null;
    public ?array $batchData = null;
    public string $previewFilter = 'all'; // 'all', 'new', 'duplicates'
    public string $selectedSectionFilter = ''; // '', 'va', 'dilr', 'qa'
    public string $selectedTopicFilter = '';
    public array $selectedIndexes = [];
    public ?array $importSummary = null;
    public ?int $viewingQuestionIndex = null;

    // Direct Test Creation Settings (Default: unchecked)
    public bool $createTestDirectly = false;
    public string $testTitle = '';
    public string $testExamCategory = 'cat';
    public int $testTotalDuration = 120;
    public array $sectionQuotas = ['va' => 24, 'dilr' => 20, 'qa' => 22];
    public array $sectionDurations = ['va' => 40, 'dilr' => 40, 'qa' => 40];
    public bool $testHasCalculator = true;
    public bool $testSectionLocked = true;

    public function dryRunUpload(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|max:20480', // 20MB max
        ]);

        $rawContent = file_get_contents($this->uploadedFile->getRealPath());
        $fileName = $this->uploadedFile->getClientOriginalName();

        $this->parseAndAnalyze($rawContent, $fileName);
    }

    protected function parseAndAnalyze(string $rawContent, string $fileName): void
    {
        $manager = new ImportParserManager();
        $parser = $manager->getParserForFile($fileName);

        $batch = $parser->parse($rawContent, $fileName);

        // Run duplicate detection
        $detector = new DuplicateDetector();
        $batch = $detector->detect($batch);

        // Compute section breakdown & topic breakdown
        $sectionCounts = ['va' => 0, 'dilr' => 0, 'qa' => 0];
        $topicCounts = [];

        foreach ($batch->questions as $q) {
            $cat = $q->sectionCategory->value;
            $sectionCounts[$cat] = ($sectionCounts[$cat] ?? 0) + 1;

            foreach ($q->topics as $topic) {
                $topicCounts[$topic] = ($topicCounts[$topic] ?? 0) + 1;
            }
        }
        arsort($topicCounts);

        // Initialize Test Builder defaults based on file metadata
        $this->testTitle = $batch->testMeta['title'] ?? pathinfo($fileName, PATHINFO_FILENAME);
        $this->testTotalDuration = (int) ($batch->testMeta['duration_minutes'] ?? 120);
        $this->sectionQuotas = [
            'va' => min($sectionCounts['va'], 24) ?: $sectionCounts['va'],
            'dilr' => min($sectionCounts['dilr'], 20) ?: $sectionCounts['dilr'],
            'qa' => min($sectionCounts['qa'], 22) ?: $sectionCounts['qa'],
        ];
        $this->sectionDurations = [
            'va' => 40,
            'dilr' => 40,
            'qa' => 40,
        ];
        $this->createTestDirectly = false; // Default unchecked

        // Infer exam category from filename or title
        $lowerTitle = strtolower($this->testTitle . ' ' . $fileName);
        if (str_contains($lowerTitle, 'snap')) {
            $this->testExamCategory = 'snap';
        } elseif (str_contains($lowerTitle, 'cmat')) {
            $this->testExamCategory = 'cmat';
        } elseif (str_contains($lowerTitle, 'xat')) {
            $this->testExamCategory = 'xat';
        } elseif (str_contains($lowerTitle, 'nmat')) {
            $this->testExamCategory = 'nmat';
        } else {
            $this->testExamCategory = 'cat';
        }

        // Convert DTO to array for Livewire serialization
        $this->batchData = [
            'sourceFileName' => $batch->sourceFileName,
            'totalQuestions' => $batch->totalQuestions,
            'newQuestionsCount' => $batch->newQuestionsCount,
            'duplicateQuestionsCount' => $batch->duplicateQuestionsCount,
            'invalidQuestionsCount' => $batch->invalidQuestionsCount,
            'sectionCounts' => $sectionCounts,
            'topicCounts' => $topicCounts,
            'passagesCount' => count($batch->passages),
            'testTitle' => $this->testTitle,
            'passages' => $batch->passages,
            'topics' => $batch->topics,
            'questions' => array_map(function ($q, $index) {
                return [
                    'index' => $index,
                    'externalId' => $q->externalId,
                    'content' => $q->content,
                    'snippet' => mb_strimwidth(strip_tags(html_entity_decode($q->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 90, '...'),
                    'type' => $q->type->value,
                    'options' => $q->options,
                    'correctAnswer' => $q->correctAnswer,
                    'explanation' => $q->explanation,
                    'difficulty' => $q->difficulty,
                    'sectionCategory' => $q->sectionCategory->value,
                    'topics' => $q->topics,
                    'passageExternalId' => $q->passageExternalId,
                    'passageContent' => $q->passageContent,
                    'isDuplicate' => $q->isDuplicate,
                    'duplicateOfId' => $q->duplicateOfId,
                ];
            }, $batch->questions, array_keys($batch->questions)),
        ];

        // Select all non-duplicate items by default
        $this->selectedIndexes = [];
        foreach ($this->batchData['questions'] as $idx => $q) {
            if (!$q['isDuplicate']) {
                $this->selectedIndexes[$idx] = true;
            }
        }

        $this->selectedSectionFilter = '';
        $this->selectedTopicFilter = '';
        $this->viewingQuestionIndex = null;
    }

    public function openQuestionModal(int $index): void
    {
        $this->viewingQuestionIndex = $index;
    }

    public function closeQuestionModal(): void
    {
        $this->viewingQuestionIndex = null;
    }

    public function prevQuestion(): void
    {
        if ($this->viewingQuestionIndex !== null && $this->viewingQuestionIndex > 0) {
            $this->viewingQuestionIndex--;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->viewingQuestionIndex !== null && $this->batchData && $this->viewingQuestionIndex < count($this->batchData['questions']) - 1) {
            $this->viewingQuestionIndex++;
        }
    }

    public function toggleSelectAll(): void
    {
        $filtered = $this->getFilteredQuestions();
        $allSelected = true;
        foreach ($filtered as $q) {
            if (empty($this->selectedIndexes[$q['index']])) {
                $allSelected = false;
                break;
            }
        }

        if ($allSelected) {
            foreach ($filtered as $q) {
                unset($this->selectedIndexes[$q['index']]);
            }
        } else {
            foreach ($filtered as $q) {
                $this->selectedIndexes[$q['index']] = true;
            }
        }
    }

    public function getFilteredQuestions(): array
    {
        if (!$this->batchData) return [];

        return array_filter($this->batchData['questions'], function ($q) {
            if ($this->previewFilter === 'new' && $q['isDuplicate']) return false;
            if ($this->previewFilter === 'duplicates' && !$q['isDuplicate']) return false;
            if ($this->selectedSectionFilter !== '' && $q['sectionCategory'] !== $this->selectedSectionFilter) return false;
            if ($this->selectedTopicFilter !== '' && !in_array($this->selectedTopicFilter, $q['topics'], true)) return false;

            return true;
        });
    }

    public function commitImport(): void
    {
        if (!$this->batchData) return;

        $questionsDTOs = [];
        foreach ($this->batchData['questions'] as $idx => $qArr) {
            $isSelected = !empty($this->selectedIndexes[$idx]);
            $questionsDTOs[] = new \App\Services\Import\DTOs\ParsedQuestionDTO(
                externalId: $qArr['externalId'],
                content: $qArr['content'],
                type: \App\Enums\QuestionType::from($qArr['type']),
                options: $qArr['options'],
                correctAnswer: $qArr['correctAnswer'],
                explanation: $qArr['explanation'],
                difficulty: $qArr['difficulty'],
                sectionCategory: \App\Enums\SectionCategory::from($qArr['sectionCategory']),
                topics: $qArr['topics'],
                passageExternalId: $qArr['passageExternalId'],
                passageContent: $qArr['passageContent'],
                isDuplicate: $qArr['isDuplicate'],
                duplicateOfId: $qArr['duplicateOfId'],
                selectedForImport: $isSelected,
            );
        }

        $batchDTO = new ParsedImportBatchDTO(
            sourceFileName: $this->batchData['sourceFileName'],
            questions: $questionsDTOs,
            passages: $this->batchData['passages'],
            topics: $this->batchData['topics'],
            totalQuestions: count($questionsDTOs),
        );

        $testConfig = null;
        if ($this->createTestDirectly) {
            $categoryEnum = \App\Enums\ExamCategory::tryFrom($this->testExamCategory) ?? \App\Enums\ExamCategory::CAT;
            $testConfig = new \App\Services\Import\DTOs\TestGenerationConfigDTO(
                createTest: true,
                testTitle: $this->testTitle ?: ($this->batchData['testTitle'] ?? 'Imported Mock Test'),
                examCategory: $categoryEnum,
                totalDurationMinutes: $this->testTotalDuration,
                sectionQuotas: array_map('intval', $this->sectionQuotas),
                sectionDurations: array_map('intval', $this->sectionDurations),
                hasCalculator: $this->testHasCalculator,
                isSectionLocked: $this->testSectionLocked,
                isPublished: true,
            );
        }

        $action = new CommitImportAction();
        $this->importSummary = $action->execute($batchDTO, $testConfig);

        $this->batchData = null;
        $this->uploadedFile = null;
    }

    public function resetImporter(): void
    {
        $this->batchData = null;
        $this->uploadedFile = null;
        $this->importSummary = null;
        $this->selectedIndexes = [];
        $this->viewingQuestionIndex = null;
    }

    public function render()
    {
        return view('livewire.admin.question-importer', [
            'filteredQuestions' => $this->getFilteredQuestions(),
        ]);
    }
}
