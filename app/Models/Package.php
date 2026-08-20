<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'validity_days',
        'is_free',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'validity_days' => 'integer',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function validityLabel(): string
    {
        return $this->validity_days
            ? $this->validity_days . ' days access'
            : 'Lifetime access';
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'package_test')
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_packages')
            ->withPivot('expires_at')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
