<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\BuildTestFromBlueprintAction;
use App\Enums\ExamCategory;
use App\Models\Test;
use App\Models\Topic;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class TestBuilder extends Component
{
    public int $currentStep = 1;
    public ?int $testId = null;

    // Step 1: Test Setup
    public string $title = 'CAT Full Length Mock 2';
    public string $category = 'cat';
    public int $total_duration_minutes = 120;
    public bool $has_calculator = true;

    // Step 2: Section Blueprints (3 default sections matching screenshot)
    public array $sections = [
        [
            'name' => 'Verbal Ability & Reading Comprehension',
            'section_category' => 'va',
            'order' => 1,
            'questions_count' => 24,
            'duration_minutes' => 40,
            'correct_marks' => 3.0,
            'negative_mcq_marks' => 1.0,
            'negative_tita_marks' => 0.0,
            'min_difficulty' => 2,
            'max_difficulty' => 4,
            'is_section_locked' => true,
            'allow_return' => false,
            'use_only_unused' => true,
            'instructions' => 'VARC section instructions: 40 minutes, auto-locked.',
            'topic_ids' => [],
        ],
        [
            'name' => 'Data Interpretation & Logical Reasoning',
            'section_category' => 'dilr',
            'order' => 2,
            'questions_count' => 20,
            'duration_minutes' => 40,
            'correct_marks' => 3.0,
            'negative_mcq_marks' => 1.0,
            'negative_tita_marks' => 0.0,
            'min_difficulty' => 3,
            'max_difficulty' => 5,
            'is_section_locked' => true,
            'allow_return' => false,
            'use_only_unused' => true,
            'instructions' => 'DILR section instructions: 40 minutes, caselet-based.',
            'topic_ids' => [],
        ],
        [
            'name' => 'Quantitative Aptitude',
            'section_category' => 'qa',
            'order' => 3,
            'questions_count' => 22,
            'duration_minutes' => 40,
            'correct_marks' => 3.0,
            'negative_mcq_marks' => 1.0,
            'negative_tita_marks' => 0.0,
            'min_difficulty' => 2,
            'max_difficulty' => 5,
            'is_section_locked' => true,
            'allow_return' => false,
            'use_only_unused' => true,
            'instructions' => 'QA section instructions: Calculator enabled.',
            'topic_ids' => [],
        ],
    ];

    public ?string $engineFeedback = null;
    public ?Test $generatedTest = null;

    public function mount(): void
    {
        if (request()->has('edit')) {
            $test = Test::with(['sections.questions'])->find(request('edit'));
            if ($test) {
                $this->testId = $test->id;
                $this->title = $test->title;
                $this->category = $test->category->value;
                $this->total_duration_minutes = $test->total_duration_minutes;
                $this->has_calculator = $test->has_calculator;
                $this->currentStep = 2;
            }
        }
    }

    public function goToStep(int $step): void
    {
        if ($step === 2 && empty($this->title)) {
            $this->addError('title', 'Please provide a test title first.');
            return;
        }
        $this->currentStep = $step;
    }

    public function addSection(): void
    {
        $this->sections[] = [
            'name' => 'New Section ' . (count($this->sections) + 1),
            'section_category' => 'qa',
            'order' => count($this->sections) + 1,
            'questions_count' => 20,
            'duration_minutes' => 30,
            'correct_marks' => 3.0,
            'negative_mcq_marks' => 1.0,
            'negative_tita_marks' => 0.0,
            'min_difficulty' => 2,
            'max_difficulty' => 4,
            'is_section_locked' => true,
            'allow_return' => false,
            'use_only_unused' => true,
            'instructions' => '',
            'topic_ids' => [],
        ];
    }

    public function removeSection(int $index): void
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    public function runEngine(BuildTestFromBlueprintAction $action): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string',
        ]);

        $test = Test::updateOrCreate(
            ['id' => $this->testId],
            [
                'title' => $this->title,
                'slug' => Str::slug($this->title) . '-' . Str::random(5),
                'category' => $this->category,
                'total_duration_minutes' => $this->total_duration_minutes,
                'has_calculator' => $this->has_calculator,
                'is_published' => false,
            ]
        );

        $this->testId = $test->id;

        // Execute Blueprint snapshot action
        $result = $action->execute($test, $this->sections);

        $this->engineFeedback = $result['message'];
        $this->generatedTest = $test->fresh(['sections.questions']);
        $this->currentStep = 3;
    }

    public function publishTest(): void
    {
        if ($this->testId) {
            $test = Test::findOrFail($this->testId);
            $test->update(['is_published' => true]);
            session()->flash('message', 'Test blueprint successfully built and published live!');
            $this->redirectRoute('admin.tests');
        }
    }

    public function render()
    {
        return view('livewire.admin.test-builder', [
            'topics' => Topic::orderBy('name')->get(),
        ]);
    }
}
