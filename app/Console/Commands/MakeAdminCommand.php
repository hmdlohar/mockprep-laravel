<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email? : The email address of the admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user or update/promote an existing user and reset their password';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Enter admin email address', 'hmdlohar@gmail.com');
        }

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address provided.');
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $this->info("User [{$email}] found. Updating role to Admin and resetting password...");
        } else {
            $this->info("Creating new Administrator account for [{$email}]...");
        }

        $password = $this->secret('Enter Admin Password');
        if (empty($password) || strlen($password) < 6) {
            $this->error('Password must be at least 6 characters.');
            return self::FAILURE;
        }

        $confirmPassword = $this->secret('Confirm Admin Password');
        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match.');
            return self::FAILURE;
        }

        if ($user) {
            $user->update([
                'role' => UserRole::ADMIN,
                'password' => Hash::make($password),
                'is_onboarded' => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            $this->info("✓ Administrator [{$email}] successfully updated and password changed.");
        } else {
            $name = $this->ask('Enter Admin Full Name', 'Admin Architect');

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => UserRole::ADMIN,
                'is_onboarded' => true,
                'email_verified_at' => now(),
            ]);

            $this->info("✓ New Administrator [{$name} ({$email})] created successfully!");
        }

        return self::SUCCESS;
    }
}
