<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ExamAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TestResult extends Component
{
    public ExamAttempt $attempt;
    public string $filter = 'all'; // all, correct, incorrect, unattempted

    public function mount(ExamAttempt $attempt): void
    {
        $this->attempt = $attempt->load([
            'test.sections.questions.passage',
            'answers.question.topics',
            'answers.testSection',
        ]);
    }

    public function render()
    {
        $answers = $this->attempt->answers;

        $totalQuestions = $answers->count();
        $correct = $answers->where('is_correct', true)->count();
        $incorrect = $answers->where('is_correct', false)->whereNotNull('user_answer')->count();
        $unattempted = $answers->whereNull('user_answer')->count();
        $totalAttempted = $correct + $incorrect;
        $accuracy = $totalAttempted > 0 ? round(($correct / $totalAttempted) * 100, 1) : 0.0;

        // Sectional aggregation
        $sectionStats = [];
        foreach ($this->attempt->test->sections as $sec) {
            $secAnswers = $answers->where('test_section_id', $sec->id);
            $secCorrect = $secAnswers->where('is_correct', true)->count();
            $secIncorrect = $secAnswers->where('is_correct', false)->whereNotNull('user_answer')->count();
            $secAttempted = $secCorrect + $secIncorrect;
            $secScore = $secAnswers->sum('marks_awarded');

            $sectionStats[] = [
                'name' => $sec->name,
                'total' => $secAnswers->count(),
                'correct' => $secCorrect,
                'incorrect' => $secIncorrect,
                'unattempted' => $secAnswers->whereNull('user_answer')->count(),
                'score' => $secScore,
                'accuracy' => $secAttempted > 0 ? round(($secCorrect / $secAttempted) * 100, 1) : 0.0,
            ];
        }

        // Filter answers for review list
        $filteredAnswers = $answers->filter(function ($a) {
            if ($this->filter === 'correct') return $a->is_correct === true;
            if ($this->filter === 'incorrect') return $a->is_correct === false && $a->user_answer !== null;
            if ($this->filter === 'unattempted') return $a->user_answer === null;
            return true;
        });

        return view('livewire.portal.test-result', [
            'totalQuestions' => $totalQuestions,
            'correct' => $correct,
            'incorrect' => $incorrect,
            'unattempted' => $unattempted,
            'accuracy' => $accuracy,
            'sectionStats' => $sectionStats,
            'filteredAnswers' => $filteredAnswers,
        ]);
    }
}
