<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Enums\AttemptStatus;
use App\Models\Test;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public string $categoryFilter = '';

    public function render()
    {
        $user = auth()->user();

        $testsQuery = Test::where('is_published', true)->with(['sections.questions']);
        if ($this->categoryFilter) {
            $testsQuery->where('category', $this->categoryFilter);
        }

        return view('livewire.portal.dashboard', [
            'tests' => $testsQuery->latest()->get(),
            'recentAttempts' => $user->attempts()
                ->where('status', AttemptStatus::COMPLETED)
                ->with('test')
                ->latest('submitted_at')
                ->limit(5)
                ->get(),
            'stats' => [
                'mocks_taken' => $user->attempts()->where('status', AttemptStatus::COMPLETED)->count(),
                'series_owned' => $user->packages()->count(),
                'tests_available' => Test::where('is_published', true)->count(),
            ],
        ]);
    }
}
