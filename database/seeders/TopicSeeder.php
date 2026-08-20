<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            // VARC
            'Reading Comprehension',
            'Para Jumbles',
            'Para Summary',
            'Odd Sentence Out',
            'Sentence Completion',

            // DILR
            'Tables & Caselets',
            'Bar & Line Graphs',
            'Linear & Circular Arrangements',
            'Matrix Match & Puzzles',
            'Games & Tournaments',

            // QA
            'Percentages & Ratios',
            'Profit, Loss & Discount',
            'Time, Speed & Distance',
            'Time & Work',
            'Algebra & Quadratics',
            'Logarithms & Exponents',
            'Geometry & Mensuration',
            'Number Systems',
            'Permutations & Probability',
        ];

        foreach ($topics as $name) {
            Topic::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
