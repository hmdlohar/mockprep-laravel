<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Services\Import\DTOs\ParsedQuestionDTO;

class QuestionSetPicker
{
    /**
     * Pick up to $targetCount questions for a section, guaranteeing that question sets (passages) are never broken.
     *
     * @param array<int, array{dto: ParsedQuestionDTO, dbId: int}> $sectionItems
     * @param int $targetCount
     * @return array<int, int> List of chosen question DB IDs
     */
    public function pickUnbrokenQuestionIds(array $sectionItems, int $targetCount): array
    {
        if ($targetCount <= 0 || empty($sectionItems)) {
            return [];
        }

        // If target equals or exceeds available, return all
        if ($targetCount >= count($sectionItems)) {
            return array_map(fn($item) => $item['dbId'], $sectionItems);
        }

        // 1. Group into atomic units (standalone questions vs passage question sets)
        $units = [];
        $passageUnits = [];

        foreach ($sectionItems as $item) {
            $passageExtId = $item['dto']->passageExternalId;

            if ($passageExtId) {
                if (!isset($passageUnits[$passageExtId])) {
                    $unitIndex = count($units);
                    $passageUnits[$passageExtId] = $unitIndex;
                    $units[$unitIndex] = [
                        'type' => 'set',
                        'passage_id' => $passageExtId,
                        'db_ids' => [$item['dbId']],
                    ];
                } else {
                    $unitIndex = $passageUnits[$passageExtId];
                    $units[$unitIndex]['db_ids'][] = $item['dbId'];
                }
            } else {
                $units[] = [
                    'type' => 'single',
                    'passage_id' => null,
                    'db_ids' => [$item['dbId']],
                ];
            }
        }

        // 2. Greedily select complete unbroken units until we reach/closest to target count
        $selectedDbIds = [];
        $currentCount = 0;

        foreach ($units as $unit) {
            $unitSize = count($unit['db_ids']);

            // If adding this unbroken unit doesn't overshoot target, or if we haven't picked anything yet
            if (($currentCount + $unitSize <= $targetCount) || $currentCount === 0) {
                foreach ($unit['db_ids'] as $id) {
                    $selectedDbIds[] = $id;
                }
                $currentCount += $unitSize;

                if ($currentCount >= $targetCount) {
                    break;
                }
            }
        }

        return $selectedDbIds;
    }
}
