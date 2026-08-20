<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed dummy student test account for local testing
        User::updateOrCreate(
            ['email' => 'student@mockprep.com'],
            [
                'name' => 'Aarav Sharma',
                'password' => Hash::make('password'),
                'role' => UserRole::STUDENT,
                'is_onboarded' => true,
                'target_exam' => 'CAT',
                'target_year' => '2025',
                'email_verified_at' => now(),
            ]
        );
    }
}
