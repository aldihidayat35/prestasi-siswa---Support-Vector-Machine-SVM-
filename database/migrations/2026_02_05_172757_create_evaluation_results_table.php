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
        Schema::create('evaluation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('ml_models')->onDelete('cascade');

            // Split configuration
            $table->decimal('test_size', 3, 2); // 0.20 = 20%
            $table->integer('random_state')->nullable();

            // Evaluation metrics
            $table->decimal('accuracy', 5, 4);
            $table->decimal('precision_score', 5, 4);
            $table->decimal('recall', 5, 4);
            $table->decimal('f1_score', 5, 4);

            // Detailed metrics per class (JSON)
            $table->json('classification_report')->nullable();
            $table->json('confusion_matrix')->nullable();

            // Cross validation results
            $table->json('cross_validation_scores')->nullable();
            $table->decimal('cv_mean', 5, 4)->nullable();
            $table->decimal('cv_std', 5, 4)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_results');
    }
};
