<?php

declare(strict_types=1);

namespace App\Enums;

enum AnswerStatus: string
{
    case NOT_VISITED = 'not_visited';
    case NOT_ANSWERED = 'not_answered';
    case ANSWERED = 'answered';
    case MARKED_FOR_REVIEW = 'marked_for_review';
    case ANSWERED_MARKED = 'answered_marked';

    public function label(): string
    {
        return match ($this) {
            self::NOT_VISITED => 'Not Visited',
            self::NOT_ANSWERED => 'Not Answered',
            self::ANSWERED => 'Answered',
            self::MARKED_FOR_REVIEW => 'Marked for Review',
            self::ANSWERED_MARKED => 'Answered & Marked for Review',
        };
    }
}
