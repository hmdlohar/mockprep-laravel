<?php

declare(strict_types=1);

namespace App\Services\Import\Contracts;

use App\Services\Import\DTOs\ParsedImportBatchDTO;

interface QuestionImportParserInterface
{
    /**
     * Parse the given file content or file path into a structured ParsedImportBatchDTO.
     */
    public function parse(string $rawContent, string $fileName): ParsedImportBatchDTO;

    /**
     * Check if this parser can handle the given file extension / MIME type.
     */
    public function supports(string $extension, string $mimeType = ''): bool;
}
