<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnswerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'test_section_id',
        'question_id',
        'user_answer',
        'status',
        'time_spent_seconds',
        'is_correct',
        'marks_awarded',
    ];

    protected function casts(): array
    {
        return [
            'status' => AnswerStatus::class,
            'time_spent_seconds' => 'integer',
            'is_correct' => 'boolean',
            'marks_awarded' => 'string',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }

    public function testSection(): BelongsTo
    {
        return $this->belongsTo(TestSection::class, 'test_section_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
