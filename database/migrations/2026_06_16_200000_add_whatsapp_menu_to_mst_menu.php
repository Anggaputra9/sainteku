<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('mst_menu')->updateOrInsert(
            ['id' => 203],
            [
                'menu_name' => 'Pengaturan WhatsApp',
                'menu_link' => 'settings.whatsapp.index',
                'menu_icon' => 'fa-brands fa-whatsapp',
                'parent_id' => 200,
                'module_id' => null,
                'order_no' => 3,
                'is_active' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table('mst_menu')->where('id', 203)->delete();
    }
};