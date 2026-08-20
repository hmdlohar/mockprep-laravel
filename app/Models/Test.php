<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExamCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'total_duration_minutes',
        'has_calculator',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'category' => ExamCategory::class,
            'total_duration_minutes' => 'integer',
            'has_calculator' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(TestSection::class)->orderBy('order');
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_test')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
