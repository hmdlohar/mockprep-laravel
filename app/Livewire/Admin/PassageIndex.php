<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\SectionCategory;
use App\Models\Passage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class PassageIndex extends Component
{
    use WithPagination;

    public string $sectionFilter = '';
    public bool $showModal = false;
    public ?int $editingPassageId = null;

    public string $section_category = 'va';
    public string $content = '';

    protected function rules(): array
    {
        return [
            'section_category' => 'required|string|in:va,dilr',
            'content' => 'required|string',
        ];
    }

    public function openCreateModal(): void
    {
        $this->editingPassageId = null;
        $this->section_category = 'va';
        $this->content = '';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $passage = Passage::findOrFail($id);
        $this->editingPassageId = $passage->id;
        $this->section_category = $passage->section_category->value;
        $this->content = $passage->content;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'section_category' => $this->section_category,
            'content' => $this->content,
        ];

        if ($this->editingPassageId) {
            Passage::findOrFail($this->editingPassageId)->update($data);
            session()->flash('message', 'Passage updated successfully.');
        } else {
            Passage::create($data);
            session()->flash('message', 'Passage added to bank.');
        }

        $this->showModal = false;
    }

    public function deletePassage(int $id): void
    {
        Passage::findOrFail($id)->delete();
        session()->flash('message', 'Passage deleted.');
    }

    public function render()
    {
        $query = Passage::withCount('questions');

        if ($this->sectionFilter) {
            $query->where('section_category', $this->sectionFilter);
        }

        return view('livewire.admin.passage-index', [
            'passages' => $query->latest()->paginate(10),
        ]);
    }
}
