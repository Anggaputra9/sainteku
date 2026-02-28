<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trx_role_permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id');
            $table->integer('modul_id');
            $table->integer('permission_id');
            $table->boolean('allowed');

            $table->unique(['role_id', 'modul_id', 'permission_id']);

            $table->foreign('role_id')
                ->references('id')
                ->on('mst_role')
                ->onDelete('cascade');

            $table->foreign('modul_id')
                ->references('id')
                ->on('mst_module')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('ref_permission')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_role_permission');
    }
};
