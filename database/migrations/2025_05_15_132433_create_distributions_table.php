<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('ai_models')->onDelete('cascade');
            $table->foreignId('use_case_id')->constrained('use_cases')->onDelete('cascade');
            $table->string('bot_name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['model_id', 'use_case_id']);
            //! foreignId's are automatically unique in the context of the foreign key constraint
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
