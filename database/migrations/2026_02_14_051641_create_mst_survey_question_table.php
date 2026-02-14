<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mst_survey_question', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->integer('survey_id')->unique();
            $table->integer('question_number')->unique();
            $table->string('question', 100);
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_survey_question');
    }
};
