<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('model_use_case_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('ai_models')->onDelete('cascade');
            $table->foreignId('use_case_id')->constrained('use_cases')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->unique(['model_id', 'use_case_id']); // Zorgt ervoor dat je niet twee keer dezelfde combinatie kunt opslaan
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_use_case_scores');
    }
};
