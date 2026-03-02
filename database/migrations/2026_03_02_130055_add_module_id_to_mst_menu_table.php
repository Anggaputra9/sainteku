<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mst_menu', function (Blueprint $table) {
            $table->unsignedInteger('module_id')->nullable()->after('parent_id');

            $table->foreign('module_id')
                  ->references('id')
                  ->on('mst_module')
                  ->nullOnDelete();
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