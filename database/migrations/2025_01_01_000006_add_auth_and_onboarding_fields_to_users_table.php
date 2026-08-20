<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->string('target_exam', 50)->nullable()->after('phone'); // e.g. CAT, CMAT
            $table->string('target_year', 10)->nullable()->after('target_exam'); // e.g. 2025, 2026
            $table->string('college_stream', 100)->nullable()->after('target_year'); // e.g. B.Tech / BBA
            $table->boolean('is_onboarded')->default(false)->after('college_stream');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'avatar',
                'phone',
                'target_exam',
                'target_year',
                'college_stream',
                'is_onboarded',
            ]);
        });
    }
};
