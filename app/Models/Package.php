<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'is_free',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ];
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
}
