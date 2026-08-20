<?php

declare(strict_types=1);

namespace App\Services\Import\DTOs;

class ParsedImportBatchDTO
{
    /**
     * @param array<int, ParsedQuestionDTO> $questions
     * @param array<string, string> $passages [externalId => content]
     * @param array<int, string> $topics
     * @param array<string, mixed> $testMeta
     */
    public function __construct(
        public string $sourceFileName,
        public array $questions = [],
        public array $passages = [],
        public array $topics = [],
        public array $testMeta = [],
        public int $totalQuestions = 0,
        public int $newQuestionsCount = 0,
        public int $duplicateQuestionsCount = 0,
        public int $invalidQuestionsCount = 0,
    ) {}
}
