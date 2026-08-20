<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SectionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passage extends Model
{
    protected $fillable = [
        'section_category',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'section_category' => SectionCategory::class,
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
