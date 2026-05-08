<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mst_user_unit', function (Blueprint $table) {
            $table->string('user_id', 50);
            $table->string('unit_id', 4);

            $table->foreign('user_id')
                ->references('id')->on('mst_user')
                ->onDelete('cascade');

            $table->foreign('unit_id')
                ->references('id')->on('mst_unit')
                ->onDelete('cascade');

            $table->primary(['user_id', 'unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_user_unit');
    }
};