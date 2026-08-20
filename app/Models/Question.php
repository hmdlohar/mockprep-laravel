<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\QuestionType;
use App\Enums\SectionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'passage_id',
        'section_category',
        'type',
        'content',
        'options',
        'correct_answer',
        'explanation',
        'difficulty',
        'source',
        'external_id',
    ];

    protected function casts(): array
    {
        return [
            'section_category' => SectionCategory::class,
            'type' => QuestionType::class,
            'options' => 'array',
            'difficulty' => 'integer',
        ];
    }

    public function passage(): BelongsTo
    {
        return $this->belongsTo(Passage::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'question_topic');
    }

    public function testSections(): BelongsToMany
    {
        return $this->belongsToMany(TestSection::class, 'test_section_questions')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}
