<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect student to Google OAuth consent screen.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $avatar = $googleUser->getAvatar();
        $name = $googleUser->getName() ?? 'Student';

        // Check if user exists by google_id or email
        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleId,
                'avatar' => $avatar ?: $user->avatar,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            // First time registration via Google
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => Hash::make(Str::random(24)),
                'role' => UserRole::STUDENT,
                'is_onboarded' => false,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);

        if (!$user->is_onboarded && $user->isStudent()) {
            return redirect()->route('portal.onboarding');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('portal.dashboard');
    }
}
