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
        Schema::create('ml_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Admin yang training
            $table->string('name'); // Nama model
            $table->string('version', 20)->default('1.0');

            // SVM Parameters
            $table->enum('kernel', ['linear', 'poly', 'rbf', 'sigmoid'])->default('rbf');
            $table->decimal('c_parameter', 10, 4)->default(1.0); // Regularization parameter
            $table->decimal('gamma_parameter', 10, 4)->nullable(); // Kernel coefficient (for rbf, poly, sigmoid)
            $table->integer('degree')->nullable(); // Degree for poly kernel

            // Model file path
            $table->string('model_path'); // Path to .pkl file
            $table->string('scaler_path')->nullable(); // Path to scaler .pkl file

            // Training info
            $table->integer('training_samples')->default(0);
            $table->integer('testing_samples')->default(0);
            $table->decimal('test_size', 3, 2)->default(0.20); // 20% test size

            // Evaluation metrics
            $table->decimal('accuracy', 5, 4)->nullable();
            $table->decimal('precision_score', 5, 4)->nullable();
            $table->decimal('recall', 5, 4)->nullable();
            $table->decimal('f1_score', 5, 4)->nullable();

            $table->timestamp('training_date')->nullable();
            $table->boolean('is_active')->default(false); // Model aktif untuk prediksi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_models');
    }
};
