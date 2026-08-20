<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\QuestionType;
use App\Enums\SectionCategory;
use App\Models\Passage;
use App\Models\Question;
use App\Models\Topic;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class QuestionIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sectionFilter = '';
    public string $typeFilter = '';
    public string $difficultyFilter = '';
    public string $topicFilter = '';
    public string $sourceFilter = '';

    // Modal state for quick create / edit
    public bool $showFormModal = false;
    public ?int $editingQuestionId = null;

    // Form fields
    public string $section_category = 'va';
    public string $type = 'mcq';
    public ?int $passage_id = null;
    public string $content = '';
    public array $options = [
        ['id' => 'A', 'text' => ''],
        ['id' => 'B', 'text' => ''],
        ['id' => 'C', 'text' => ''],
        ['id' => 'D', 'text' => ''],
    ];
    public string $correct_answer = 'A';
    public string $explanation = '';
    public int $difficulty = 3;
    public array $selectedTopics = [];

    // Preview state
    public ?Question $previewQuestion = null;

    protected function rules(): array
    {
        return [
            'section_category' => 'required|string',
            'type' => 'required|string|in:mcq,tita',
            'passage_id' => 'nullable|exists:passages,id',
            'content' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|integer|min:1|max:5',
            'selectedTopics' => 'nullable|array',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSectionFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $question = Question::with('topics')->findOrFail($id);
        $this->editingQuestionId = $question->id;
        $this->section_category = $question->section_category->value;
        $this->type = $question->type->value;
        $this->passage_id = $question->passage_id;
        $this->content = $question->content;
        $this->options = $question->options ?? [
            ['id' => 'A', 'text' => ''],
            ['id' => 'B', 'text' => ''],
            ['id' => 'C', 'text' => ''],
            ['id' => 'D', 'text' => ''],
        ];
        $this->correct_answer = $question->correct_answer;
        $this->explanation = $question->explanation ?? '';
        $this->difficulty = $question->difficulty;
        $this->selectedTopics = $question->topics->pluck('id')->toArray();

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'section_category' => $this->section_category,
            'type' => $this->type,
            'passage_id' => $this->passage_id ?: null,
            'content' => $this->content,
            'options' => $this->type === 'mcq' ? $this->options : null,
            'correct_answer' => $this->correct_answer,
            'explanation' => $this->explanation,
            'difficulty' => $this->difficulty,
        ];

        if ($this->editingQuestionId) {
            $question = Question::findOrFail($this->editingQuestionId);
            $question->update($data);
            $question->topics()->sync($this->selectedTopics);
            session()->flash('message', 'Question updated successfully.');
        } else {
            $question = Question::create($data);
            $question->topics()->sync($this->selectedTopics);
            session()->flash('message', 'Question created successfully in the bank.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function deleteQuestion(int $id): void
    {
        Question::findOrFail($id)->delete();
        session()->flash('message', 'Question deleted from question bank.');
    }

    public function viewPreview(int $id): void
    {
        $this->previewQuestion = Question::with(['passage', 'topics'])->findOrFail($id);
    }

    public function closePreview(): void
    {
        $this->previewQuestion = null;
    }

    public function resetForm(): void
    {
        $this->editingQuestionId = null;
        $this->section_category = 'va';
        $this->type = 'mcq';
        $this->passage_id = null;
        $this->content = '';
        $this->options = [
            ['id' => 'A', 'text' => ''],
            ['id' => 'B', 'text' => ''],
            ['id' => 'C', 'text' => ''],
            ['id' => 'D', 'text' => ''],
        ];
        $this->correct_answer = 'A';
        $this->explanation = '';
        $this->difficulty = 3;
        $this->selectedTopics = [];
    }

    public function render()
    {
        $query = Question::with(['passage', 'topics']);

        if ($this->search) {
            $query->where('content', 'like', '%' . $this->search . '%')
                  ->orWhere('correct_answer', 'like', '%' . $this->search . '%');
        }

        if ($this->sectionFilter) {
            $query->where('section_category', $this->sectionFilter);
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->difficultyFilter) {
            $query->where('difficulty', $this->difficultyFilter);
        }

        if ($this->topicFilter) {
            $query->whereHas('topics', function ($q) {
                $q->where('topics.id', $this->topicFilter);
            });
        }

        if ($this->sourceFilter) {
            $query->where('source', $this->sourceFilter);
        }

        return view('livewire.admin.question-index', [
            'questions' => $query->latest()->paginate(10),
            'topics' => Topic::orderBy('name')->get(),
            'passages' => Passage::latest()->get(),
            'sources' => Question::whereNotNull('source')->distinct()->pluck('source'),
        ]);
    }
}
