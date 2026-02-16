<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('id')->get();

        // load current roles mapping
        $mappings = DB::table('trx_user_role')->pluck('role_id', 'user_id')->toArray();

        return view('masterdata::admin.users', compact('users', 'roles', 'mappings'));
    }

    public function assignRole(Request $request, $userId)
    {
        $data = $request->validate([
            'role_id' => 'required|integer',
        ]);

        DB::table('trx_user_role')->updateOrInsert(
            ['user_id' => $userId],
            ['user_id' => $userId, 'role_id' => $data['role_id']]
        );

        return redirect()->route('masterdata.admin.users.index')->with('success', 'Peran berhasil diperbarui');
    }
}
