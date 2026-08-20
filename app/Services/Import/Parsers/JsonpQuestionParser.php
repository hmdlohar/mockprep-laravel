<?php

declare(strict_types=1);

namespace App\Services\Import\Parsers;

use App\Enums\QuestionType;
use App\Enums\SectionCategory;
use App\Services\Import\Contracts\QuestionImportParserInterface;
use App\Services\Import\DTOs\ParsedImportBatchDTO;
use App\Services\Import\DTOs\ParsedQuestionDTO;

class JsonpQuestionParser implements QuestionImportParserInterface
{
    public function supports(string $extension, string $mimeType = ''): bool
    {
        return in_array(strtolower($extension), ['json', 'jsonp', 'txt'], true);
    }

    public function parse(string $rawContent, string $fileName): ParsedImportBatchDTO
    {
        $cleaned = trim($rawContent);

        // Strip JSONP callback wrapper if present e.g. dataCallback(...)
        if (preg_match('/^[a-zA-Z0-9_$]+\s*\(\s*(.*)\s*\)\s*;?\s*$/s', $cleaned, $matches)) {
            $cleaned = $matches[1];
        }

        $decoded = json_decode($cleaned, true);

        if (!is_array($decoded) || empty($decoded)) {
            return new ParsedImportBatchDTO(
                sourceFileName: $fileName,
                invalidQuestionsCount: 1
            );
        }

        $root = is_array($decoded[0] ?? null) ? $decoded[0] : $decoded;

        $testModel = $root['TestModel'][0] ?? [];
        $itemModel = $root['ItemModel'] ?? [];
        $optionModel = $root['ItemOptionModel'] ?? [];

        // Group options by ItemID
        $optionsByItem = [];
        $optionLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($optionModel as $opt) {
            $itemId = (string) ($opt['ItemID'] ?? '');
            if (!$itemId) continue;

            $optionsByItem[$itemId][] = [
                'text' => $this->rewriteImages(trim((string) ($opt['Option'] ?? ''))),
                'is_correct' => (bool) ($opt['IsCorrect'] ?? false),
            ];
        }

        $parsedQuestions = [];
        $passagesMap = [];
        $topicsSet = [];

        foreach ($itemModel as $item) {
            $itemId = (string) ($item['ItemID'] ?? uniqid('item_'));
            $itemText = $this->rewriteImages(trim((string) ($item['Item'] ?? '')));

            if (empty($itemText)) {
                continue;
            }

            // Detect Question Type
            $rawType = strtoupper(trim((string) ($item['ItemType'] ?? 'MCQ')));
            $type = in_array($rawType, ['NE', 'TITA', 'NUMERIC', 'FIB', 'FILL_BLANK', 'NAT'], true)
                ? QuestionType::TITA
                : QuestionType::MCQ;

            // Options & Correct Answer
            $rawOpts = $optionsByItem[$itemId] ?? [];
            $formattedOptions = [];
            $correctAnswer = null;

            if ($type === QuestionType::MCQ) {
                foreach ($rawOpts as $idx => $optData) {
                    $letter = $optionLetters[$idx] ?? chr(65 + $idx);
                    $formattedOptions[] = [
                        'id' => $letter,
                        'text' => $optData['text'],
                    ];
                    if ($optData['is_correct']) {
                        $correctAnswer = $letter;
                    }
                }
            } else {
                // TITA question -> extract numerical key from explanation or correct answer
                $correctAnswer = $this->extractTitaAnswer((string) ($item['Explanation'] ?? ''));
            }

            // Map Section Category
            $sectionCat = $this->mapSectionCategory(
                (string) ($item['Subject'] ?? ''),
                (string) ($item['Area'] ?? ''),
                (string) ($item['Topic'] ?? '')
            );

            // Topics collection
            $topics = [];
            if (!empty($item['Topic'])) {
                $t = trim((string) $item['Topic']);
                $topics[] = $t;
                $topicsSet[$t] = true;
            }
            if (!empty($item['Area'])) {
                $a = trim((string) $item['Area']);
                $topics[] = $a;
                $topicsSet[$a] = true;
            }

            // Passage set mapping
            $passageId = null;
            $passageText = $this->rewriteImages(trim((string) ($item['Passage'] ?? '')));
            if (!empty($passageText) && strlen(strip_tags($passageText)) > 5) {
                $passageId = (string) ($item['ItemPassageID'] ?? md5($passageText));
                $passagesMap[$passageId] = $passageText;
            }

            // Difficulty LOD (1 to 5)
            $lod = (int) ($item['LOD'] ?? 2);
            $difficulty = max(1, min(5, $lod > 0 ? $lod : 2));

            $explanation = !empty($item['Explanation']) ? $this->rewriteImages(trim((string) $item['Explanation'])) : null;

            $parsedQuestions[] = new ParsedQuestionDTO(
                externalId: $itemId,
                content: $itemText,
                type: $type,
                options: $formattedOptions,
                correctAnswer: $correctAnswer,
                explanation: $explanation,
                difficulty: $difficulty,
                sectionCategory: $sectionCat,
                topics: array_unique($topics),
                passageExternalId: $passageId,
                passageContent: $passageText ?: null,
            );
        }

        return new ParsedImportBatchDTO(
            sourceFileName: $fileName,
            questions: $parsedQuestions,
            passages: $passagesMap,
            topics: array_keys($topicsSet),
            testMeta: [
                'title' => $testModel['Name'] ?? pathinfo($fileName, PATHINFO_FILENAME),
                'duration_minutes' => (int) ($testModel['TestDuration'] ?? 120),
            ],
            totalQuestions: count($parsedQuestions),
            newQuestionsCount: count($parsedQuestions),
        );
    }

    protected function mapSectionCategory(string $subject, string $area, string $topic): SectionCategory
    {
        $combined = strtolower("{$subject} {$area} {$topic}");

        if (str_contains($combined, 'verbal') || str_contains($combined, 'rc') || str_contains($combined, 'reading') || str_contains($combined, 'english') || str_contains($combined, 'vocabulary') || str_contains($combined, 'grammar') || str_contains($combined, 'analogies')) {
            return SectionCategory::VA;
        }

        if (str_contains($combined, 'data interpretation') || str_contains($combined, 'di') || str_contains($combined, 'logical') || str_contains($combined, 'reasoning') || str_contains($combined, 'dilr') || str_contains($combined, 'lr') || str_contains($combined, 'ar')) {
            return SectionCategory::DILR;
        }

        return SectionCategory::QA;
    }

    protected function extractTitaAnswer(string $explanation): ?string
    {
        if (preg_match('/(?:Hence|answer is|correct value is|total is)\s*[:=]?\s*([0-9]+(?:\.[0-9]+)?)/i', $explanation, $matches)) {
            return $matches[1];
        }
        return '0';
    }

    public function rewriteImages(?string $html): string
    {
        if (empty($html) || !str_contains($html, '<img')) {
            return (string) $html;
        }

        return (string) preg_replace_callback(
            '/<img\b([^>]*?)src=["\']([^"\']*)["\']/i',
            function ($matches) {
                $prefix = $matches[1];
                $src = $matches[2];
                $newSrc = $this->resolveImageSrc($src);
                return "<img{$prefix}src=\"{$newSrc}\"";
            },
            $html
        );
    }

    public function resolveImageSrc(string $src): string
    {
        $clean = trim($src);
        if (empty($clean) || str_starts_with($clean, 'data:')) {
            return $src;
        }

        if (str_contains($clean, 'QUIZKYIMAGEREPO')) {
            $parts = explode('QUIZKYIMAGEREPO/', $clean);
            $fileName = preg_split('/[ "]/', $parts[1] ?? '')[0] ?? '';
            if (empty($fileName)) {
                return $clean;
            }
            $resolvedUrl = 'http://campusquizkybv.blob.core.windows.net/catgurusitemimages/' . $fileName;
        } else {
            $resolvedUrl = rtrim($clean, '\\');
        }

        return 'https://indivedor.netlify.app/imgs/' . base64_encode($resolvedUrl) . '.png';
    }
}
