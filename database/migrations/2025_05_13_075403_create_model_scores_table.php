<?php

// database/migrations/xxxx_xx_xx_create_model_scores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('model_scores', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('use_case');
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_scores');
    }
};

