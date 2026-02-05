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
        Schema::create('ml_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('model_id')->constrained('ml_models')->onDelete('cascade');
            $table->foreignId('predicted_by')->constrained('users')->onDelete('cascade'); // User yang melakukan prediksi

            // Input features for this prediction
            $table->json('input_features');

            // Prediction result
            $table->string('predicted_label', 20); // Rendah, Sedang, Tinggi
            $table->decimal('confidence_score', 5, 4)->nullable(); // Confidence level (0-1)
            $table->json('probability_scores')->nullable(); // Probability for each class

            // Comparison with actual (if available)
            $table->string('actual_label', 20)->nullable();
            $table->boolean('is_correct')->nullable();

            $table->text('recommendation')->nullable(); // Rekomendasi akademik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_predictions');
    }
};
