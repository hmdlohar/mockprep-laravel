<?php

declare(strict_types=1);

namespace App\Services\Import\DTOs;

use App\Enums\ExamCategory;

class TestGenerationConfigDTO
{
    /**
     * @param array<string, int> $sectionQuotas e.g. ['va' => 24, 'dilr' => 20, 'qa' => 22]
     * @param array<string, int> $sectionDurations e.g. ['va' => 40, 'dilr' => 40, 'qa' => 40]
     */
    public function __construct(
        public bool $createTest = false,
        public string $testTitle = '',
        public ExamCategory $examCategory = ExamCategory::CAT,
        public int $totalDurationMinutes = 120,
        public array $sectionQuotas = [],
        public array $sectionDurations = [],
        public bool $hasCalculator = true,
        public bool $isSectionLocked = true,
        public bool $isPublished = true,
    ) {}
}
