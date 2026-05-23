<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Lepaskan menu "Ujian" dari permission module Monev Akademik / Bank Soal.
     *
     * Sebelumnya menu Ujian (id=23) memakai module_id=2, sama dengan
     * Tashih Soal & Bank Soal. Akibatnya sidebar item "Ujian" hanya muncul
     * untuk user yang punya permission Read di module 2 — termasuk dosen
     * yang me-review soal. Mahasiswa biasanya tidak punya permission ini,
     * jadi mereka tidak melihat menu Ujian.
     *
     * Solusi: set module_id = null. Menu yang module_id null bebas dari
     * filter permission RBAC dan akan tampil untuk siapa saja yang login —
     * persis sama treatment-nya dengan Dashboard. Otorisasi action di
     * dalam Ujian (siapa boleh kelola room vs. siapa cuma bisa join)
     * sudah ditangani di controller (RoomController::guardLecturer).
     */
    public function up(): void
    {
        DB::table('mst_menu')
            ->where('id', 23)
            ->update(['module_id' => null]);
    }

    public function down(): void
    {
        DB::table('mst_menu')
            ->where('id', 23)
            ->update(['module_id' => 2]);
    }
};
