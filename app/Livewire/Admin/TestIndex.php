<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Test;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class TestIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = '';

    public function togglePublish(int $id): void
    {
        $test = Test::findOrFail($id);
        $test->update(['is_published' => !$test->is_published]);
        session()->flash('message', "Test '{$test->title}' status updated.");
    }

    public function deleteTest(int $id): void
    {
        Test::findOrFail($id)->delete();
        session()->flash('message', 'Test paper deleted.');
    }

    public function render()
    {
        $query = Test::with(['sections.questions']);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        return view('livewire.admin.test-index', [
            'tests' => $query->latest()->paginate(10),
        ]);
    }
}
