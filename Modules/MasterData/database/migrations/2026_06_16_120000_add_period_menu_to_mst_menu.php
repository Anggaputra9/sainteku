<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('mst_menu')->updateOrInsert(
            ['id' => 14],
            [
                'menu_name' => 'Tahun Akademik',
                'menu_link' => 'masterdata.periods.index',
                'menu_icon' => null,
                'parent_id' => 1,
                'module_id' => null,
                'order_no' => 7,
                'is_active' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('mst_menu')->where('id', 14)->delete();
    }
};