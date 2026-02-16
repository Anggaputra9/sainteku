<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignRoleToFirstUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = DB::table('mst_user')->first();
        if ($user) {
            DB::table('trx_user_role')->updateOrInsert([
                'user_id' => $user->id,
            ], [
                'user_id' => $user->id,
                'role_id' => 1,
            ]);
        }
    }
}
