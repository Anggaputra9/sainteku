<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * trx_exam_attempt_events = log aktivitas mahasiswa selama ujian.
     * Dipakai untuk audit & ditampilkan di halaman monitoring dosen.
     */
    public function up(): void
    {
        Schema::create('trx_exam_attempt_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');

            $table->string('event_type', 30)
                ->comment('focus_lost, focus_regain, fullscreen_exit, copy, paste, auto_submit, manual_submit, start');
            $table->json('payload')->nullable();
            $table->dateTime('occurred_at');

            $table->foreign('attempt_id')->references('id')->on('trx_exam_attempts')->onDelete('cascade');
            $table->index(['attempt_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_exam_attempt_events');
    }
};
