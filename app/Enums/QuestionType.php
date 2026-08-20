<?php

declare(strict_types=1);

namespace App\Enums;

enum QuestionType: string
{
    case MCQ = 'mcq';
    case TITA = 'tita';

    public function label(): string
    {
        return match ($this) {
            self::MCQ => 'Multiple Choice Question',
            self::TITA => 'Type In The Answer',
        };
    }
}
