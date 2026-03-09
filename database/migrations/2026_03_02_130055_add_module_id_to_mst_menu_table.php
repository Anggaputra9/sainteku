<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mst_menu', function (Blueprint $table) {
            // 1. Buat kolomnya
            $table->integer('module_id')->nullable();

            // 2. Buat relasinya dengan nama custom agar tidak Error 121
            $table->foreign('module_id', 'fk_custom_menu_module')
                ->references('id')
                ->on('mst_module')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('mst_menu', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
};
