<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan tabel.
     */
    public function up(): void
    {
        // 1. Ubah nama kolom lama agar sesuai dengan kebutuhan baru
        Schema::table('mst_inventory', function (Blueprint $table) {
            $table->renameColumn('description', 'item_name'); // Menjadi Nama Barang
            $table->renameColumn('quantity', 'stock');        // Menjadi Stok
        });

        // 2. Tambahkan kolom-kolom baru
        Schema::table('mst_inventory', function (Blueprint $table) {
            $table->string('brand', 100)->nullable()->after('inventory_type'); // Merk Barang
            $table->string('unit_measure', 50)->nullable()->after('brand');    // Satuan (pcs, unit, dll)
            $table->string('photo', 255)->nullable()->after('unit_measure');   // Foto Barang
            $table->text('description')->nullable()->after('photo');           // Deskripsi Panjang
            $table->integer('price')->default(0)->after('stock');              // Harga
            $table->tinyInteger('status')->default(1)->after('price');         // Status (1=Aktif, 0=Rusak)
            $table->string('unit_id', 4)->nullable()->after('status');         // Relasi ke Unit

            // Set Foreign Key untuk unit_id
            $table->foreign('unit_id')->references('id')->on('mst_unit')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Kembalikan perubahan jika di-rollback.
     */
    public function down(): void
    {
        Schema::table('mst_inventory', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn([
                'brand', 'unit_measure', 'photo', 'description', 
                'price', 'status', 'unit_id'
            ]);
        });

        Schema::table('mst_inventory', function (Blueprint $table) {
            $table->renameColumn('item_name', 'description');
            $table->renameColumn('stock', 'quantity');
        });
    }
};