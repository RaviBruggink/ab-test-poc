<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('model_use_case_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('ai_models')->cascadeOnDelete();
            $table->foreignId('use_case_id')->constrained('use_cases')->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->index(['model_id', 'use_case_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_use_case_scores');
    }
};
