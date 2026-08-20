<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ExamAttempt;
use App\Models\Test;
use App\Models\User;
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
        $this->test = $test->load(['sections.questions']);
    }

    public function startExam(): void
    {
        if (!$this->agreed) {
            $this->addError('agreed', 'Please accept the declaration before starting the exam.');
            return;
        }

        // Get or default to first student user for emulation
        $user = User::where('role', 'student')->first() ?? User::first();

        // Create an attempt
        $attempt = ExamAttempt::create([
            'user_id' => $user->id,
            'test_id' => $this->test->id,
            'status' => \App\Enums\AttemptStatus::IN_PROGRESS,
            'started_at' => now(),
        ]);

        $this->redirectRoute('cbt.runner', ['attempt' => $attempt->id]);
    }

    public function render()
    {
        return view('livewire.portal.test-instructions');
    }
}
