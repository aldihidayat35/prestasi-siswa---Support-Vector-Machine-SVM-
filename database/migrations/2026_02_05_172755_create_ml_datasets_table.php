<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ml_datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('learning_activity_id')->constrained('learning_activities')->onDelete('cascade');
            $table->foreignId('academic_score_id')->constrained('academic_scores')->onDelete('cascade');

            // Features (X) - stored as JSON for flexibility
            $table->json('features'); // {attendance_rate, study_duration, task_frequency, discussion_participation, media_usage, discipline_score}

            // Label (Y) - Target variable
            $table->string('label', 20); // Rendah, Sedang, Tinggi

            $table->boolean('is_training')->default(true); // true = training, false = testing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_datasets');
    }
};
