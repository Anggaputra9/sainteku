<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Tambah kolom UUID ke trx_exam_rooms & trx_exam_attempts.
 *
 * Tujuan:
 * - Room dipanggil di route admin pakai UUID, bukan auto-increment id.
 *   Lebih aman dari enumerasi (tebak id 1,2,3 ...).
 * - Hasil ujian (attempt) juga dipanggil pakai UUID supaya
 *   link hasil tidak mudah ditebak.
 *
 * Untuk join mahasiswa kita tetap pakai room_code (6 char) karena
 * itu memang dirancang untuk diketik pendek / di-encode jadi QR.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('trx_exam_rooms') && !Schema::hasColumn('trx_exam_rooms', 'uuid')) {
            Schema::table('trx_exam_rooms', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });

            // Backfill untuk row yang sudah ada
            DB::table('trx_exam_rooms')->whereNull('uuid')->orderBy('id')->each(function ($row) {
                DB::table('trx_exam_rooms')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            });

            Schema::table('trx_exam_rooms', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->unique()->change();
            });
        }

        if (Schema::hasTable('trx_exam_attempts') && !Schema::hasColumn('trx_exam_attempts', 'uuid')) {
            Schema::table('trx_exam_attempts', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });

            DB::table('trx_exam_attempts')->whereNull('uuid')->orderBy('id')->each(function ($row) {
                DB::table('trx_exam_attempts')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            });

            Schema::table('trx_exam_attempts', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->unique()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('trx_exam_rooms', 'uuid')) {
            Schema::table('trx_exam_rooms', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            });
        }
        if (Schema::hasColumn('trx_exam_attempts', 'uuid')) {
            Schema::table('trx_exam_attempts', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            });
        }
    }
};
