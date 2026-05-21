<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mst_menu', function (Blueprint $table) {
            if (!Schema::hasColumn('mst_menu', 'module_id')) {
                $table->integer('module_id')->nullable()->after('parent_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_menu', function (Blueprint $table) {
            if (Schema::hasColumn('mst_menu', 'module_id')) {
                $table->dropColumn('module_id');
            }
        });
    }
};
