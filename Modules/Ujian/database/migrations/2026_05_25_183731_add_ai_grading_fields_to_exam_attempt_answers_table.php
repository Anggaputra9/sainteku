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
        Schema::table('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->enum('grading_method', ['manual', 'ai'])->nullable()->after('grader_note')
                ->comment('Metode koreksi: manual atau ai');
            $table->text('ai_feedback')->nullable()->after('grading_method')
                ->comment('Feedback dari AI');
            $table->string('graded_by', 20)->nullable()->after('ai_feedback')
                ->comment('User ID yang melakukan koreksi manual');
            $table->timestamp('graded_at')->nullable()->after('graded_by')
                ->comment('Waktu koreksi dilakukan');

            $table->foreign('graded_by')->references('id')->on('mst_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['grading_method', 'ai_feedback', 'graded_by', 'graded_at']);
        });
    }
};
