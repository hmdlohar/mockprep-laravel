<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSection extends Model
{
    protected $fillable = [
        'test_id',
        'name',
        'order',
        'duration_minutes',
        'correct_marks',
        'negative_mcq_marks',
        'negative_tita_marks',
        'is_section_locked',
        'allow_return',
        'instructions',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'duration_minutes' => 'integer',
            'correct_marks' => 'string',
            'negative_mcq_marks' => 'string',
            'negative_tita_marks' => 'string',
            'is_section_locked' => 'boolean',
            'allow_return' => 'boolean',
        ];
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'test_section_questions')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function sectionQuestions(): HasMany
    {
        return $this->hasMany(TestSectionQuestion::class)->orderBy('order');
    }
}
