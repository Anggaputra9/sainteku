<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->string('selected_option', 1)->nullable();
            $table->string('grading_method', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->dropColumn('selected_option');
        });
    }
};
