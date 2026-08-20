<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Enums\SectionCategory;
use App\Models\Passage;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    public function run(): void
    {
        // 1. VARC Passage & Questions
        $rcPassage = Passage::create([
            'section_category' => SectionCategory::VA,
            'content' => '<p class="lead">Understanding the relationship between consciousness and quantum mechanics has long perplexed physicists and philosophers alike. While classical physics treats reality as deterministic and independent of observation, quantum phenomena suggest an intrinsic entanglement between the observer and the observed.</p><p>Sir Roger Penrose and Stuart Hameroff proposed the Orch-OR (Orchestrated Objective Reduction) theory, positing that quantum computations within cellular microtubules are the biological basis for conscious experience. Critics argue that warm biological environments cause decoherence far too rapidly for quantum states to persist. Nonetheless, recent biomolecular spectroscopy exhibits quantum coherence in photosynthetic complexes, reopening the debate over quantum biology.</p>',
        ]);

        $rcTopic = Topic::where('slug', 'reading-comprehension')->first();

        $q1 = Question::create([
            'passage_id' => $rcPassage->id,
            'section_category' => SectionCategory::VA,
            'type' => QuestionType::MCQ,
            'content' => '<p>According to the passage, what is the primary objection raised against the Orch-OR theory of consciousness?</p>',
            'options' => [
                ['id' => 'A', 'text' => 'Microtubules lack the molecular structure required for biochemical processing.'],
                ['id' => 'B', 'text' => 'Warm biological temperatures cause rapid quantum decoherence.'],
                ['id' => 'C', 'text' => 'Classical mechanics sufficiently explains all biological neural processing.'],
                ['id' => 'D', 'text' => 'Photosynthetic complexes disprove the existence of quantum states in neurons.'],
            ],
            'correct_answer' => 'B',
            'explanation' => '<p>The passage explicitly states: <em>"Critics argue that warm biological environments cause decoherence far too rapidly for quantum states to persist."</em> Hence Option B is correct.</p>',
            'difficulty' => 3,
        ]);
        if ($rcTopic) $q1->topics()->attach($rcTopic->id);

        $q2 = Question::create([
            'passage_id' => $rcPassage->id,
            'section_category' => SectionCategory::VA,
            'type' => QuestionType::MCQ,
            'content' => '<p>The author mentions photosynthetic complexes in the second paragraph primarily to:</p>',
            'options' => [
                ['id' => 'A', 'text' => 'Prove that Penrose and Hameroff were undeniably correct.'],
                ['id' => 'B', 'text' => 'Demonstrate that quantum coherence can indeed occur in warm biological systems.'],
                ['id' => 'C', 'text' => 'Show that plants possess rudimentary consciousness.'],
                ['id' => 'D', 'text' => 'Refute the deterministic principles of classical physics.'],
            ],
            'correct_answer' => 'B',
            'explanation' => '<p>Spectroscopy in photosynthetic systems shows quantum coherence in biological environments, challenging the critique that quantum phenomena cannot survive warm biology.</p>',
            'difficulty' => 4,
        ]);
        if ($rcTopic) $q2->topics()->attach($rcTopic->id);

        // VARC Standalone Para Jumble (TITA)
        $pjTopic = Topic::where('slug', 'para-jumbles')->first();
        $q3 = Question::create([
            'passage_id' => null,
            'section_category' => SectionCategory::VA,
            'type' => QuestionType::TITA,
            'content' => '<p><strong>The four sentences given below form a coherent paragraph. Key in the correct order of numbers (e.g., 2314):</strong></p><ol class="list-decimal pl-5 space-y-1"><li>This transition has drastically shortened product lifecycles and altered market demand dynamics.</li><li>Traditionally, manufacturing firms relied heavily on historical sales data to forecast future quarterly supply.</li><li>Today, hyper-connected consumers demand near-instantaneous customization and real-time fulfillment.</li><li>Consequently, modern logistics networks must operate on agile, predictive neural algorithms rather than static spreadsheets.</li></ol>',
            'options' => null,
            'correct_answer' => '2314',
            'explanation' => '<p>Sentence 2 introduces traditional forecasting. Sentence 3 presents the modern shift in consumer demand. Sentence 1 explains the consequence of this shift on product lifecycles. Sentence 4 provides the concluding imperative for logistics systems. Order: <strong>2314</strong>.</p>',
            'difficulty' => 3,
        ]);
        if ($pjTopic) $q3->topics()->attach($pjTopic->id);

        // 2. DILR Caselet & Questions
        $dilrPassage = Passage::create([
            'section_category' => SectionCategory::DILR,
            'content' => '<p class="font-semibold mb-2">Direction: Analyze the table below regarding smartphone sales (in thousands of units) across 4 brands in 2024:</p><table class="w-full border-collapse border border-slate-300 text-sm"><thead><tr class="bg-slate-100"><th class="border p-2">Brand</th><th class="border p-2">Q1</th><th class="border p-2">Q2</th><th class="border p-2">Q3</th><th class="border p-2">Q4</th></tr></thead><tbody><tr><td class="border p-2 font-medium">Apex</td><td class="border p-2">120</td><td class="border p-2">140</td><td class="border p-2">150</td><td class="border p-2">190</td></tr><tr><td class="border p-2 font-medium">Bolt</td><td class="border p-2">80</td><td class="border p-2">110</td><td class="border p-2">130</td><td class="border p-2">160</td></tr><tr><td class="border p-2 font-medium">Cipher</td><td class="border p-2">150</td><td class="border p-2">130</td><td class="border p-2">140</td><td class="border p-2">180</td></tr><tr><td class="border p-2 font-medium">Dyno</td><td class="border p-2">90</td><td class="border p-2">100</td><td class="border p-2">120</td><td class="border p-2">140</td></tr></tbody></table>',
        ]);

        $dilrTopic = Topic::where('slug', 'tables-caselets')->first();

        $q4 = Question::create([
            'passage_id' => $dilrPassage->id,
            'section_category' => SectionCategory::DILR,
            'type' => QuestionType::MCQ,
            'content' => '<p>Which brand registered the highest percentage increase in sales from Q1 to Q4?</p>',
            'options' => [
                ['id' => 'A', 'text' => 'Apex'],
                ['id' => 'B', 'text' => 'Bolt'],
                ['id' => 'C', 'text' => 'Cipher'],
                ['id' => 'D', 'text' => 'Dyno'],
            ],
            'correct_answer' => 'B',
            'explanation' => '<p>Bolt sales went from 80 to 160, which is an increase of ((160 - 80) / 80) * 100 = <strong>100%</strong>. Apex is 58.3%, Cipher is 20%, Dyno is 55.5%.</p>',
            'difficulty' => 2,
        ]);
        if ($dilrTopic) $q4->topics()->attach($dilrTopic->id);

        $q5 = Question::create([
            'passage_id' => $dilrPassage->id,
            'section_category' => SectionCategory::DILR,
            'type' => QuestionType::TITA,
            'content' => '<p>What is the total number of smartphone units (in thousands) sold across all four brands combined in Q3?</p>',
            'options' => null,
            'correct_answer' => '540',
            'explanation' => '<p>Q3 total = 150 + 130 + 140 + 120 = <strong>540</strong>.</p>',
            'difficulty' => 1,
        ]);
        if ($dilrTopic) $q5->topics()->attach($dilrTopic->id);

        // 3. QA Questions
        $qaPercTopic = Topic::where('slug', 'percentages-ratios')->first();
        $q6 = Question::create([
            'passage_id' => null,
            'section_category' => SectionCategory::QA,
            'type' => QuestionType::MCQ,
            'content' => '<p>A merchant increases the price of an article by 40% and then offers a discount of 25% on the marked price. If the final selling price is $630, what was the original cost price?</p>',
            'options' => [
                ['id' => 'A', 'text' => '$500'],
                ['id' => 'B', 'text' => '$550'],
                ['id' => 'C', 'text' => '$600'],
                ['id' => 'D', 'text' => '$650'],
            ],
            'correct_answer' => 'C',
            'explanation' => '<p>Let CP = X. Marked Price = 1.40X. Selling Price = 1.40X * 0.75 = 1.05X.<br>1.05X = 630 => X = 630 / 1.05 = <strong>$600</strong>.</p>',
            'difficulty' => 2,
        ]);
        if ($qaPercTopic) $q6->topics()->attach($qaPercTopic->id);

        $qaAlgTopic = Topic::where('slug', 'algebra-quadratics')->first();
        $q7 = Question::create([
            'passage_id' => null,
            'section_category' => SectionCategory::QA,
            'type' => QuestionType::TITA,
            'content' => '<p>If &alpha; and &beta; are the roots of the quadratic equation \(x^2 - 14x + 45 = 0\), find the value of \(\alpha^2 + \beta^2\).</p>',
            'options' => null,
            'correct_answer' => '106',
            'explanation' => '<p>Sum of roots \(\alpha + \beta = 14\). Product of roots \(\alpha \beta = 45\).<br>\(\alpha^2 + \beta^2 = (\alpha + \beta)^2 - 2\alpha\beta = 14^2 - 2(45) = 196 - 90 = \mathbf{106}\).</p>',
            'difficulty' => 2,
        ]);
        if ($qaAlgTopic) $q7->topics()->attach($qaAlgTopic->id);
    }
}
