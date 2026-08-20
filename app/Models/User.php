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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Non-expired owned package IDs (memoized per request).
     *
     * @return array<int, int>
     */
    public function activePackageIds(): array
    {
        return once(function () {
            return $this->packages()
                ->where(function ($query) {
                    $query->whereNull('user_packages.expires_at')
                        ->orWhere('user_packages.expires_at', '>', now());
                })
                ->pluck('packages.id')
                ->all();
        });
    }

    /**
     * Single source of truth: IDs of tests the user may attempt
     * (tests inside any free published package OR any owned active package).
     *
     * @return array<int, int>
     */
    public function accessibleTestIds(): array
    {
        return once(function () {
            $query = Test::query()
                ->where('is_published', true)
                ->whereHas('packages', function ($q) {
                    $q->where(function ($sq) {
                        $sq->where(function ($fq) {
                            $fq->where('is_free', true)->where('is_published', true);
                        })->orWhereIn('packages.id', $this->activePackageIds());
                    });
                });

            return $query->pluck('id')->all();
        });
    }

    public function canAccessTest(Test $test): bool
    {
        return in_array($test->id, $this->accessibleTestIds(), true);
    }

    public function ownsPackage(Package $package): bool
    {
        return in_array($package->id, $this->activePackageIds(), true);
    }
}
