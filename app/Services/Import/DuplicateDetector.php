<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Models\Question;
use App\Services\Import\DTOs\ParsedImportBatchDTO;

class DuplicateDetector
{
    /**
     * Scan the parsed questions batch and mark any duplicates against existing DB records.
     */
    public function detect(ParsedImportBatchDTO $batch): ParsedImportBatchDTO
    {
        // Load existing questions with source and external_id
        $existingQuestions = Question::select(['id', 'content', 'source', 'external_id'])->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'source' => $q->source,
                'external_id' => $q->external_id,
                'normalized' => $this->normalizeContent($q->content),
            ];
        });

        $duplicatesCount = 0;
        $newCount = 0;

        foreach ($batch->questions as $questionDTO) {
            $currentNorm = $this->normalizeContent($questionDTO->content);
            $extId = $questionDTO->externalId;
            $srcFile = $batch->sourceFileName;

            $matched = $existingQuestions->first(function ($existing) use ($currentNorm, $extId, $srcFile) {
                // 1. Direct Source & External ID match
                if (!empty($extId) && !empty($existing['external_id']) && $existing['external_id'] === $extId && $existing['source'] === $srcFile) {
                    return true;
                }

                // 2. Content similarity match
                if (empty($currentNorm) || empty($existing['normalized'])) return false;
                return ($existing['normalized'] === $currentNorm) 
                    || (strlen($currentNorm) > 40 && str_contains($existing['normalized'], substr($currentNorm, 0, 40)));
            });

            if ($matched) {
                $questionDTO->isDuplicate = true;
                $questionDTO->duplicateOfId = $matched['id'];
                $questionDTO->selectedForImport = false; // Default unselected for duplicate
                $duplicatesCount++;
            } else {
                $questionDTO->isDuplicate = false;
                $questionDTO->selectedForImport = true;
                $newCount++;
            }
        }

        $batch->duplicateQuestionsCount = $duplicatesCount;
        $batch->newQuestionsCount = $newCount;

        return $batch;
    }

    public function normalizeContent(string $html): string
    {
        $clean = strip_tags(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $clean = preg_replace('/\s+/', ' ', $clean);
        return strtolower(trim((string) $clean));
    }
}
