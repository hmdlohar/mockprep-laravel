<?php

declare(strict_types=1);

namespace App\Enums;

enum ExamCategory: string
{
    case CAT = 'cat';
    case CMAT = 'cmat';
    case XAT = 'xat';
    case SNAP = 'snap';
    case NMAT = 'nmat';
    case IIFT = 'iift';
    case MHCET = 'mhcet';

    public function label(): string
    {
        return match ($this) {
            self::CAT => 'CAT (Common Admission Test)',
            self::CMAT => 'CMAT (Common Management Admission Test)',
            self::XAT => 'XAT (Xavier Aptitude Test)',
            self::SNAP => 'SNAP (Symbiosis National Aptitude Test)',
            self::NMAT => 'NMAT by GMAC',
            self::IIFT => 'IIFT Entrance Exam',
            self::MHCET => 'MAH MBA CET',
        };
    }
}
