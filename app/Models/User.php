<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'phone',
        'target_exam',
        'target_year',
        'college_stream',
        'is_onboarded',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_onboarded' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::STUDENT;
    }

    public function homeUrl(): string
    {
        return match (true) {
            $this->isAdmin() => route('admin.dashboard'),
            $this->isStudent() && !$this->is_onboarded => route('portal.onboarding'),
            default => route('portal.dashboard'),
        };
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'user_packages')
            ->withPivot('expires_at')
            ->withTimestamps();
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
