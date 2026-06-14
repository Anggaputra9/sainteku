<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trx_exam_rooms', function (Blueprint $table) {
            $table->dateTime('started_at')->nullable()->after('start_at')
                ->comment('Waktu aktual ujian dimulai (otomatis atau manual dosen)');
        });

        DB::table('trx_exam_rooms')
            ->whereIn('status', ['PUBLISHED', 'CLOSED'])
            ->whereNull('started_at')
            ->update(['started_at' => DB::raw('start_at')]);
    }

    public function down(): void
    {
        Schema::table('trx_exam_rooms', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
};