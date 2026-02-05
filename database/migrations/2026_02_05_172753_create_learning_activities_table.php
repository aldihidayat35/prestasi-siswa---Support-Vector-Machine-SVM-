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
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade'); // Guru yang menginput

            // Variabel Input (X) - Aktivitas Belajar
            $table->decimal('attendance_rate', 5, 2); // Kehadiran siswa (%)
            $table->decimal('study_duration', 5, 2); // Durasi belajar harian (jam)
            $table->integer('task_frequency'); // Frekuensi mengerjakan tugas
            $table->decimal('discussion_participation', 5, 2); // Partisipasi diskusi (skor 0-100)
            $table->decimal('media_usage', 5, 2); // Penggunaan media pembelajaran (skor 0-100)
            $table->decimal('discipline_score', 5, 2); // Kedisiplinan belajar (skor 0-100)

            $table->string('period', 20); // Semester/Periode: 2024-1, 2024-2
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'period']); // Satu record per siswa per periode
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_activities');
    }
};
