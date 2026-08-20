<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExamCategory;
use App\Enums\SectionCategory;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestSection;
use App\Models\TestSectionQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $test = Test::updateOrCreate(
            ['slug' => 'cat-2025-full-length-mock-1'],
            [
                'title' => 'CAT 2025 Full Length Mock 1 (IMP-MSZN23V8)',
                'category' => ExamCategory::CAT,
                'total_duration_minutes' => 120,
                'has_calculator' => true,
                'is_published' => true,
            ]
        );

        // Section 1: VARC
        $secVarc = TestSection::updateOrCreate(
            ['test_id' => $test->id, 'order' => 1],
            [
                'name' => 'Verbal Ability & Reading Comprehension',
                'duration_minutes' => 40,
                'correct_marks' => 3.00,
                'negative_mcq_marks' => 1.00,
                'negative_tita_marks' => 0.00,
                'is_section_locked' => true,
                'allow_return' => false,
                'instructions' => 'This section contains VARC questions. You have 40 minutes. You cannot navigate to other sections until this section is completed.',
            ]
        );

        // Section 2: DILR
        $secDilr = TestSection::updateOrCreate(
            ['test_id' => $test->id, 'order' => 2],
            [
                'name' => 'Data Interpretation & Logical Reasoning',
                'duration_minutes' => 40,
                'correct_marks' => 3.00,
                'negative_mcq_marks' => 1.00,
                'negative_tita_marks' => 0.00,
                'is_section_locked' => true,
                'allow_return' => false,
                'instructions' => 'This section contains DILR questions. You have 40 minutes.',
            ]
        );

        // Section 3: QA
        $secQa = TestSection::updateOrCreate(
            ['test_id' => $test->id, 'order' => 3],
            [
                'name' => 'Quantitative Aptitude',
                'duration_minutes' => 40,
                'correct_marks' => 3.00,
                'negative_mcq_marks' => 1.00,
                'negative_tita_marks' => 0.00,
                'is_section_locked' => true,
                'allow_return' => false,
                'instructions' => 'This section contains Quantitative Aptitude questions. Standard on-screen calculator is enabled.',
            ]
        );

        // Snapshot Questions into Sections
        $varcQuestions = Question::where('section_category', SectionCategory::VA)->get();
        $order = 1;
        foreach ($varcQuestions as $q) {
            TestSectionQuestion::updateOrCreate(
                ['test_section_id' => $secVarc->id, 'question_id' => $q->id],
                ['order' => $order++]
            );
        }

        $dilrQuestions = Question::where('section_category', SectionCategory::DILR)->get();
        $order = 1;
        foreach ($dilrQuestions as $q) {
            TestSectionQuestion::updateOrCreate(
                ['test_section_id' => $secDilr->id, 'question_id' => $q->id],
                ['order' => $order++]
            );
        }

        $qaQuestions = Question::where('section_category', SectionCategory::QA)->get();
        $order = 1;
        foreach ($qaQuestions as $q) {
            TestSectionQuestion::updateOrCreate(
                ['test_section_id' => $secQa->id, 'question_id' => $q->id],
                ['order' => $order++]
            );
        }
    }
}
