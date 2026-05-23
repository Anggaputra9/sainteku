<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * trx_exam_rooms = ruang ujian yang dibuat dosen.
     * Satu paket soal (proposal) yang sudah APPROVED bisa dipakai
     * berulang kali → tinggal bikin room baru menunjuk proposal yang sama.
     */
    public function up(): void
    {
        Schema::create('trx_exam_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 12)->unique()->comment('Kode unik untuk join mahasiswa');
            $table->unsignedBigInteger('proposal_id');
            $table->string('title', 150);
            $table->text('description')->nullable();

            $table->string('created_by', 50)->comment('user_id dosen pembuat room');

            // Jadwal & durasi
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedSmallInteger('duration_minutes')->comment('Durasi pengerjaan setelah mahasiswa start');

            // Anti tab switch
            $table->enum('tab_switch_policy', ['unlimited', 'strict', 'limited'])->default('strict')
                ->comment('unlimited=tidak ada batas, strict=langsung auto submit, limited=pakai counter');
            $table->unsignedSmallInteger('tab_switch_limit')->default(0)
                ->comment('Toleransi pelanggaran (hanya dipakai saat policy=limited)');

            // Tampilan & perilaku
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('show_remaining_time')->default(true);

            // Status
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'CLOSED'])->default('DRAFT');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('trx_exam_proposals')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('mst_user')->onDelete('restrict');

            $table->index(['status', 'is_active']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_exam_rooms');
    }
};
