<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * trx_exam_attempts = sesi pengerjaan ujian per mahasiswa.
     * 1 mahasiswa hanya boleh punya 1 attempt per room.
     */
    public function up(): void
    {
        Schema::create('trx_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('user_id', 50);

            $table->dateTime('started_at')->nullable();
            $table->dateTime('expires_at')->nullable()->comment('started_at + duration');
            $table->dateTime('submitted_at')->nullable();

            $table->enum('status', [
                'NOT_STARTED',
                'ONGOING',
                'SUBMITTED',
                'AUTO_SUBMITTED_TIME',
                'AUTO_SUBMITTED_VIOLATION',
            ])->default('NOT_STARTED');

            $table->unsignedInteger('tab_switch_count')->default(0);
            $table->dateTime('last_activity_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->decimal('score', 5, 2)->nullable()->comment('Diisi setelah grading manual oleh dosen');
            $table->text('grader_note')->nullable();

            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('trx_exam_rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('mst_user')->onDelete('cascade');

            $table->unique(['room_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_exam_attempts');
    }
};
