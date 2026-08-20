<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case CREATED = 'created';
    case PAID = 'paid';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
        };
    }
}
