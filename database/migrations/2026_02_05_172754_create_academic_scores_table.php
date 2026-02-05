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
        Schema::create('academic_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('semester', 20); // Semester: 2024-1, 2024-2
            $table->decimal('score', 5, 2); // Nilai rata-rata rapor (0-100)
            $table->enum('category', ['Rendah', 'Sedang', 'Tinggi']); // Kategori prestasi
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_scores');
    }
};
