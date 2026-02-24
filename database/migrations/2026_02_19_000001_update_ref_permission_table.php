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
        // Add columns to ref_permission if not exists
        if (Schema::hasTable('ref_permission')) {
            Schema::table('ref_permission', function (Blueprint $table) {
                if (!Schema::hasColumn('ref_permission', 'permission_code')) {
                    $table->string('permission_code', 2)->nullable()->after('id');
                }
                if (!Schema::hasColumn('ref_permission', 'permission_name')) {
                    $table->string('permission_name', 50)->after('permission_code');
                }
                if (!Schema::hasColumn('ref_permission', 'description')) {
                    $table->text('description')->nullable()->after('permission_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ref_permission', function (Blueprint $table) {
            if (Schema::hasColumn('ref_permission', 'permission_code')) {
                $table->dropColumn('permission_code');
            }
            if (Schema::hasColumn('ref_permission', 'permission_name')) {
                $table->dropColumn('permission_name');
            }
            if (Schema::hasColumn('ref_permission', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

