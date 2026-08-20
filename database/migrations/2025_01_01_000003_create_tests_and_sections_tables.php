<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 50)->index(); // 'cat', 'cmat', 'xat'
            $table->unsignedInteger('total_duration_minutes');
            $table->boolean('has_calculator')->default(true);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('test_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->string('name', 100);
            $table->unsignedTinyInteger('order')->default(1);
            $table->unsignedInteger('duration_minutes');
            $table->decimal('correct_marks', 4, 2)->default(3.00);
            $table->decimal('negative_mcq_marks', 4, 2)->default(1.00);
            $table->decimal('negative_tita_marks', 4, 2)->default(0.00);
            $table->boolean('is_section_locked')->default(true);
            $table->boolean('allow_return')->default(false);
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->index(['test_id', 'order']);
        });

        Schema::create('test_section_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_section_id')->constrained('test_sections')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->unsignedInteger('order')->default(1);

            $table->unique(['test_section_id', 'question_id']);
            $table->index(['test_section_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_section_questions');
        Schema::dropIfExists('test_sections');
        Schema::dropIfExists('tests');
    }
};
