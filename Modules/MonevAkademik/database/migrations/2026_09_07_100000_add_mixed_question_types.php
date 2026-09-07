<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trx_exam_proposals', function (Blueprint $table) {
            $table->string('exam_type', 10)->change();
        });
        Schema::table('trx_questions', function (Blueprint $table) {
            // Existing questions and their free-text answers remain essays.
            $table->string('question_type', 20)->default('essay');
            $table->json('options')->nullable();
            $table->string('correct_option', 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('trx_questions', function (Blueprint $table) {
            $table->dropColumn(['question_type', 'options', 'correct_option']);
        });
        // Keep the widened exam_type column: narrowing would destroy QUIZ data.
    }
};
