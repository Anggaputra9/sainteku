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
        Schema::table('trx_exam_rooms', function (Blueprint $table) {
            $table->boolean('auto_grading_enabled')->default(false)->after('show_remaining_time')
                ->comment('Aktifkan koreksi otomatis dengan AI setelah mahasiswa submit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_exam_rooms', function (Blueprint $table) {
            $table->dropColumn('auto_grading_enabled');
        });
    }
};
