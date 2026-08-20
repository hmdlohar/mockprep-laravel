<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\AttemptStatus;
use App\Enums\QuestionType;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\DB;

class EvaluateExamAttemptAction
{
    /**
     * Evaluate the exam attempt, score all answers, and record final marks.
     *
     * @param ExamAttempt $attempt
     * @return array{total_score: float, accuracy: float, total_questions: int, correct: int, incorrect: int, unattempted: int}
     */
    public function execute(ExamAttempt $attempt): array
    {
        return DB::transaction(function () use ($attempt) {
            $attempt->load(['test.sections.questions', 'answers.question', 'answers.testSection']);

            $totalScore = 0.0;
            $correctCount = 0;
            $incorrectCount = 0;
            $unattemptedCount = 0;

            // Ensure all questions in the test have an attempt_answer entry
            foreach ($attempt->test->sections as $section) {
                foreach ($section->questions as $question) {
                    $answer = $attempt->answers->firstWhere('question_id', $question->id);

                    if (!$answer) {
                        $answer = $attempt->answers()->create([
                            'test_section_id' => $section->id,
                            'question_id' => $question->id,
                            'status' => \App\Enums\AnswerStatus::NOT_VISITED,
                            'user_answer' => null,
                            'is_correct' => false,
                            'marks_awarded' => 0.00,
                        ]);
                    }

                    // Evaluate correctness if answered
                    $userAnswer = trim((string) $answer->user_answer);
                    $correctAnswer = trim((string) $question->correct_answer);

                    if ($userAnswer === '') {
                        $unattemptedCount++;
                        $answer->update([
                            'is_correct' => null,
                            'marks_awarded' => 0.00,
                        ]);
                        continue;
                    }

                    $isCorrect = (strcasecmp($userAnswer, $correctAnswer) === 0);

                    if ($isCorrect) {
                        $marks = (float) $section->correct_marks;
                        $totalScore += $marks;
                        $correctCount++;
                        $answer->update([
                            'is_correct' => true,
                            'marks_awarded' => number_format($marks, 2, '.', ''),
                        ]);
                    } else {
                        // Negative marks only apply to MCQ, TITA has 0 negative marks
                        $negative = ($question->type === QuestionType::MCQ) ? (float) $section->negative_mcq_marks : 0.0;
                        $totalScore -= $negative;
                        $incorrectCount++;
                        $answer->update([
                            'is_correct' => false,
                            'marks_awarded' => number_format(-$negative, 2, '.', ''),
                        ]);
                    }
                }
            }

            $totalAttempted = $correctCount + $incorrectCount;
            $accuracy = $totalAttempted > 0 ? round(($correctCount / $totalAttempted) * 100, 1) : 0.0;

            // Finalize attempt
            $attempt->update([
                'status' => AttemptStatus::COMPLETED,
                'submitted_at' => now(),
                'total_score' => number_format($totalScore, 2, '.', ''),
            ]);

            return [
                'total_score' => $totalScore,
                'accuracy' => $accuracy,
                'total_questions' => $correctCount + $incorrectCount + $unattemptedCount,
                'correct' => $correctCount,
                'incorrect' => $incorrectCount,
                'unattempted' => $unattemptedCount,
            ];
        });
    }
}
