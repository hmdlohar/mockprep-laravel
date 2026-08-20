<?php

declare(strict_types=1);

namespace App\Enums;

enum SectionCategory: string
{
    case VA = 'va';
    case DILR = 'dilr';
    case QA = 'qa';

    public function label(): string
    {
        return match ($this) {
            self::VA => 'Verbal Ability & Reading Comprehension',
            self::DILR => 'Data Interpretation & Logical Reasoning',
            self::QA => 'Quantitative Aptitude',
        };
    }
}
