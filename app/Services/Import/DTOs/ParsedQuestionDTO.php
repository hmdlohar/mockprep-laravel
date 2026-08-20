<?php

declare(strict_types=1);

namespace App\Services\Import\DTOs;

use App\Enums\QuestionType;
use App\Enums\SectionCategory;

class ParsedQuestionDTO
{
    /**
     * @param array<int, array{id: string, text: string}> $options
     * @param array<int, string> $topics
     */
    public function __construct(
        public string $externalId,
        public string $content,
        public QuestionType $type,
        public array $options,
        public ?string $correctAnswer,
        public ?string $explanation,
        public int $difficulty,
        public SectionCategory $sectionCategory,
        public array $topics,
        public ?string $passageExternalId = null,
        public ?string $passageContent = null,
        public bool $isDuplicate = false,
        public ?int $duplicateOfId = null,
        public bool $selectedForImport = true,
    ) {}
}
