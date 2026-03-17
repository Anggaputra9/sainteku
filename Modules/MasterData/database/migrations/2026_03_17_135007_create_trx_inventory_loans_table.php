<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('trx_inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code', 30)->unique();
            
            // Relasi ke tabel Master (Panjang karakter disesuaikan dengan mst_user dan mst_inventory)
            $table->string('user_id', 50); 
            $table->string('inventory_id', 5); 
            
            // Detail Peminjaman
            $table->integer('quantity')->default(1);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('purpose');
            
            // Status: 0=Pending, 1=ACC, 2=Tolak, 3=Dikembalikan
            $table->tinyInteger('status')->default(0); 
            
            // Tracking Persetujuan
            $table->string('approved_by', 50)->nullable(); 
            $table->text('admin_note')->nullable(); 
            
            $table->timestamps();

            // Definisi Foreign Key
            $table->foreign('user_id')->references('id')->on('mst_user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventory_id')->references('id')->on('mst_inventory')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('mst_user')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Kembalikan perubahan jika di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_inventory_loans');
    }
};