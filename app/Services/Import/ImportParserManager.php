<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Services\Import\Contracts\QuestionImportParserInterface;
use App\Services\Import\Parsers\JsonpQuestionParser;
use InvalidArgumentException;

class ImportParserManager
{
    /**
     * @var array<int, QuestionImportParserInterface>
     */
    protected array $parsers = [];

    public function __construct()
    {
        // Register default parsers (extensible for Excel, CSV, PDF in future)
        $this->registerParser(new JsonpQuestionParser());
    }

    public function registerParser(QuestionImportParserInterface $parser): void
    {
        $this->parsers[] = $parser;
    }

    public function getParserForFile(string $fileName, string $mimeType = ''): QuestionImportParserInterface
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        foreach ($this->parsers as $parser) {
            if ($parser->supports($extension, $mimeType)) {
                return $parser;
            }
        }

        throw new InvalidArgumentException("No supported parser found for file format: .{$extension}");
    }
}
