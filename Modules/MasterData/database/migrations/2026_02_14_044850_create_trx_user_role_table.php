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
        Schema::create('trx_user_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 20);
            $table->integer('role_id');
            $table->string('unit_id', 4)->nullable();

            $table->unique(['user_id', 'role_id']);

            // FK ke mst_user
            $table->foreign('user_id')
                ->references('id')
                ->on('mst_user')
                ->onDelete('cascade');

            // FK ke mst_role
            $table->foreign('role_id')
                ->references('id')
                ->on('mst_role')
                ->onDelete('cascade');

            // FK ke mst_unit (optional)
            $table->foreign('unit_id')
                ->references('id')
                ->on('mst_unit')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_user_role');
    }
};
