<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passage_id')->nullable()->constrained('passages')->nullOnDelete();
            $table->string('section_category', 50)->index(); // 'va', 'dilr', 'qa'
            $table->string('type', 20)->default('mcq')->index(); // 'mcq', 'tita'
            $table->longText('content');
            $table->json('options')->nullable(); // null for TITA
            $table->text('correct_answer');
            $table->longText('explanation')->nullable();
            $table->unsignedTinyInteger('difficulty')->default(1)->index(); // 1 to 5
            $table->timestamps();
        });

        Schema::create('question_topic', function (Blueprint $table) {
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->primary(['question_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_topic');
        Schema::dropIfExists('questions');
    }
};
