<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Test;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $package = Package::updateOrCreate(
            ['slug' => 'cat-2025-all-india-mock-series'],
            [
                'title' => 'CAT 2025 All India Mock Series',
                'description' => 'Comprehensive mock test series featuring 20 Full-Length Mocks and 30 Sectional Tests calibrated to latest IIM CAT difficulty levels.',
                'price' => 1999.00,
                'is_free' => false,
                'is_published' => true,
            ]
        );

        $test = Test::where('slug', 'cat-2025-full-length-mock-1')->first();
        if ($test) {
            $package->tests()->syncWithoutDetaching([
                $test->id => ['order' => 1],
            ]);
        }

        // Grant enrollment to sample student
        $student = User::where('email', 'student@mockprep.com')->first();
        if ($student) {
            UserPackage::updateOrCreate(
                ['user_id' => $student->id, 'package_id' => $package->id],
                ['expires_at' => now()->addMonths(6)]
            );
        }
    }
}
