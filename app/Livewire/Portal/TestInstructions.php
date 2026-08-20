<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Enums\AttemptStatus;
use App\Models\ExamAttempt;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TestInstructions extends Component
{
    public Test $test;
    public bool $agreed = false;

    public function mount(Test $test): void
    {
        abort_unless($test->is_published, 404);

        if (!Auth::user()->canAccessTest($test)) {
            session()->flash('access_error', 'This mock test is part of a paid test series. Buy the series to unlock it.');
            $this->redirectRoute('portal.series');

            return;
        }

        $this->test = $test->load(['sections.questions']);
    }

    public function startExam(): void
    {
        if (!$this->agreed) {
            $this->addError('agreed', 'Please accept the declaration before starting the exam.');
            return;
        }

        $attempt = ExamAttempt::create([
            'user_id' => Auth::id(),
            'test_id' => $this->test->id,
            'status' => AttemptStatus::IN_PROGRESS,
            'started_at' => now(),
        ]);

        $this->redirectRoute('cbt.runner', ['attempt' => $attempt->id]);
    }

    public function render()
    {
        return view('livewire.portal.test-instructions');
    }
}
