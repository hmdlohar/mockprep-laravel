<?php

declare(strict_types=1);

namespace App\Livewire\Cbt;

use App\Actions\EvaluateExamAttemptAction;
use App\Enums\AnswerStatus;
use App\Enums\AttemptStatus;
use App\Models\AttemptAnswer;
use App\Models\ExamAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.cbt')]
class ExamRunner extends Component
{
    public ExamAttempt $attempt;
    public array $initialPayload = [];
    public bool $showSubmitModal = false;

    public function mount(ExamAttempt $attempt): void
    {
        $this->attempt = $attempt->load(['test.sections.questions.passage', 'user']);

        if ($this->attempt->status === AttemptStatus::COMPLETED) {
            $this->redirectRoute('portal.test.result', ['attempt' => $this->attempt->id]);
            return;
        }

        // Initialize attempt_answers if not yet created
        $this->initAttemptAnswers();

        // Build complete client payload for 0ms zero-latency rendering
        $this->buildPayload();
    }

    protected function initAttemptAnswers(): void
    {
        foreach ($this->attempt->test->sections as $section) {
            foreach ($section->questions as $q) {
                AttemptAnswer::firstOrCreate(
                    [
                        'attempt_id' => $this->attempt->id,
                        'question_id' => $q->id,
                    ],
                    [
                        'test_section_id' => $section->id,
                        'status' => AnswerStatus::NOT_VISITED,
                        'user_answer' => null,
                        'time_spent_seconds' => 0,
                    ]
                );
            }
        }
    }

    protected function buildPayload(): void
    {
        $savedAnswers = AttemptAnswer::where('attempt_id', $this->attempt->id)
            ->get()
            ->keyBy('question_id');

        $sectionsData = [];
        foreach ($this->attempt->test->sections as $sec) {
            $questionsData = [];
            foreach ($sec->questions as $q) {
                $saved = $savedAnswers->get($q->id);
                $questionsData[] = [
                    'id' => $q->id,
                    'section_id' => $sec->id,
                    'type' => $q->type->value,
                    'content' => $q->content,
                    'options' => $q->options,
                    'difficulty' => $q->difficulty,
                    'passage' => $q->passage ? [
                        'id' => $q->passage->id,
                        'content' => $q->passage->content,
                    ] : null,
                    'initial_answer' => $saved?->user_answer,
                    'initial_status' => $saved ? (is_string($saved->status) ? $saved->status : $saved->status->value) : 'not_visited',
                ];
            }

            $sectionsData[] = [
                'id' => $sec->id,
                'name' => $sec->name,
                'order' => $sec->order,
                'duration_minutes' => $sec->duration_minutes,
                'correct_marks' => (float) $sec->correct_marks,
                'negative_mcq_marks' => (float) $sec->negative_mcq_marks,
                'negative_tita_marks' => (float) $sec->negative_tita_marks,
                'is_section_locked' => (bool) $sec->is_section_locked,
                'allow_return' => (bool) $sec->allow_return,
                'questions' => $questionsData,
            ];
        }

        $this->initialPayload = [
            'attempt_id' => $this->attempt->id,
            'test_title' => $this->attempt->test->title,
            'has_calculator' => (bool) $this->attempt->test->has_calculator,
            'candidate_name' => $this->attempt->user->name,
            'sections' => $sectionsData,
        ];
    }

    /**
     * Non-blocking background sync for a single question answer.
     */
    public function syncAnswer(int $questionId, ?string $userAnswer, string $status, int $timeSpent = 0): void
    {
        AttemptAnswer::where('attempt_id', $this->attempt->id)
            ->where('question_id', $questionId)
            ->update([
                'user_answer' => $userAnswer ?: null,
                'status' => $status,
                'time_spent_seconds' => $timeSpent,
            ]);
    }

    /**
     * Batch sync all answers and finalize exam submission.
     */
    public function submitExam(array $answersBatch = []): void
    {
        // Flush all client state answers to database
        foreach ($answersBatch as $qId => $item) {
            AttemptAnswer::where('attempt_id', $this->attempt->id)
                ->where('question_id', (int) $qId)
                ->update([
                    'user_answer' => $item['answer'] ?? null,
                    'status' => $item['status'] ?? 'not_answered',
                    'time_spent_seconds' => (int) ($item['time_spent'] ?? 0),
                ]);
        }

        // Run scoring evaluation action
        $evaluator = app(EvaluateExamAttemptAction::class);
        $evaluator->execute($this->attempt);

        $this->redirectRoute('portal.test.result', ['attempt' => $this->attempt->id]);
    }

    public function render()
    {
        return view('livewire.cbt.exam-runner');
    }
}
