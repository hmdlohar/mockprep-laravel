<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateImpersonationTokenAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function __construct(
        private readonly CreateImpersonationTokenAction $tokens,
    ) {
    }

    public function __invoke(string $token): RedirectResponse
    {
        $userId = $this->tokens->consume($token);

        abort_unless($userId !== null, 404, 'This login link is invalid or has expired.');

        $user = User::findOrFail($userId);

        Auth::login($user);
        session()->regenerate();

        return redirect()->to($user->homeUrl());
    }
}
