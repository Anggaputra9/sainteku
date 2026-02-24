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
        // Fix the unique constraints on trx_role_permission
        // Check which indexes actually exist before dropping
        $conn = \Illuminate\Support\Facades\DB::connection();
        $stmt = $conn->getPdo()->query("SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE  TABLE_NAME = 'trx_role_permission'");
        $indexes = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        Schema::table('trx_role_permission', function (Blueprint $table) use ($indexes) {
            if (in_array('trx_role_permission_role_id_unique', $indexes)) {
                $table->dropUnique('trx_role_permission_role_id_unique');
            }
            if (in_array('trx_role_permission_modul_id_unique', $indexes)) {
                $table->dropUnique('trx_role_permission_modul_id_unique');
            }
            if (in_array('trx_role_permission_permission_id_unique', $indexes)) {
                $table->dropUnique('trx_role_permission_permission_id_unique');
            }
        });

        // Clear existing data
        \Illuminate\Support\Facades\DB::table('trx_role_permission')->truncate();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
