<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\ExamAttempt;
use App\Models\Package;
use App\Models\Passage;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalQuestions' => Question::count(),
            'totalTests' => Test::count(),
            'totalPassages' => Passage::count(),
            'totalPackages' => Package::count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalAttempts' => ExamAttempt::count(),
            'recentTests' => Test::withCount('sections')->latest()->take(5)->get(),
        ]);
    }
}
