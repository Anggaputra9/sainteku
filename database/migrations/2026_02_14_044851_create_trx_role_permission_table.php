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
            $table->integer('role_id')->unique();
            $table->integer('modul_id')->unique();
            $table->integer('permission_id')->unique();
            $table->boolean('allowed');
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
