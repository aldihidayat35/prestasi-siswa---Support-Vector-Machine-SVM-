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
        Schema::table('ml_predictions', function (Blueprint $table) {
            // Change from decimal(5,4) to decimal(6,2) to support 0-100 percentage format
            $table->decimal('confidence_score', 6, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ml_predictions', function (Blueprint $table) {
            $table->decimal('confidence_score', 5, 4)->nullable()->change();
        });
    }
};
