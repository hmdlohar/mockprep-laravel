<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Package;
use App\Models\Test;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TestCatalog extends Component
{
    public string $categoryFilter = '';

    public function render()
    {
        $testsQuery = Test::where('is_published', true)->with(['sections.questions']);
        if ($this->categoryFilter) {
            $testsQuery->where('category', $this->categoryFilter);
        }

        return view('livewire.portal.test-catalog', [
            'tests' => $testsQuery->latest()->get(),
            'packages' => Package::where('is_published', true)->withCount('tests')->get(),
        ]);
    }
}
