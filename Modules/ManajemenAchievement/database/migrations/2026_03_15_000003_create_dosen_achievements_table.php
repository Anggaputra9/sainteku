<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dosen_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20);
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('tingkat_id');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->string('penyelenggara')->nullable();
            $table->string('url')->nullable();

            // Untuk publikasi (Jurnal/Prosiding)
            $table->enum('jenis_publikasi', ['scopus', 'sinta', 'lainnya'])->nullable();
            $table->string('nama_jurnal')->nullable();
            $table->string('volume')->nullable();
            $table->string('nomor')->nullable();
            $table->string('halaman')->nullable();
            $table->string('issn')->nullable();

            // Untuk HKI/Paten
            $table->string('nomor_pendaftaran')->nullable();
            $table->string('status_hki')->nullable();

            // Untuk Buku
            $table->string('isbn')->nullable();
            $table->string('penerbit')->nullable();
            $table->integer('jumlah_halaman')->nullable();

            // File
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_penolakan')->nullable();
            $table->string('approved_by', 20)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('unit_id', 10)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mst_user')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('dosen_kategori');
            $table->foreign('tingkat_id')->references('id')->on('dosen_tingkat');
            $table->foreign('approved_by')->references('id')->on('mst_user')->onDelete('set null');

            $table->index('status');
            $table->index('unit_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen_achievements');
    }
};
