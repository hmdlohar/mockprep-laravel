<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreateImpersonationTokenAction
{
    public const int TTL_MINUTES = 5;

    private const string CACHE_PREFIX = 'impersonation:';

    public function execute(User $user): string
    {
        $token = Str::random(64);

        Cache::put(self::CACHE_PREFIX . $token, $user->id, now()->addMinutes(self::TTL_MINUTES));

        return route('impersonate', $token);
    }

    public function consume(string $token): ?int
    {
        $userId = Cache::pull(self::CACHE_PREFIX . $token);

        return is_int($userId) ? $userId : null;
    }
}
