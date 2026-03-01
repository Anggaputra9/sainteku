<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of all users
     */
   public function index(Request $request)
    {
        $query = User::with('roles')->orderBy('name');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $users = $query->paginate(10)->withQueryString();
        $roles = Role::all(); 
    
        // Masukkan $roles ke dalam compact
        return view('masterdata::admin.users', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::orderBy('id')->get();
        return view('masterdata::admin.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:mst_user,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mst_user,email',
            'password' => 'required|string|min:8|confirmed',
            'identity_id' => 'nullable|string',
            'user_type' => 'nullable|string',
            'unit_id' => 'nullable|string',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:mst_role,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Extract role_ids separately
        $roleIds = $data['role_ids'];
        unset($data['role_ids']);

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Set default values for nullable fields
        $data['identity_id'] = $data['identity_id'] ?? null;
        $data['user_type'] = $data['user_type'] ?? null;
        $data['unit_id'] = $data['unit_id'] ?? null;
        $data['is_active'] = $data['is_active'] ?? false;

        // Create user
        $user = User::create($data);

        // Assign roles
        $user->roles()->sync($roleIds);

        return redirect()->route('masterdata.admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('id')->get();
        $userRoles = DB::table('trx_user_role')->where('user_id', $id)->pluck('role_id')->toArray();

        return view('masterdata::admin.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mst_user,email,' . $id . ',id',
            'password' => 'nullable|string|min:8|confirmed',
            'identity_id' => 'nullable|string',
            'user_type' => 'nullable|string',
            'unit_id' => 'nullable|string',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:mst_role,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Extract role_ids separately
        $roleIds = $data['role_ids'];
        unset($data['role_ids']);

        // Only hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Set default values for nullable fields
        if (!isset($data['identity_id']) || is_null($data['identity_id'])) {
            $data['identity_id'] = null;
        }
        if (!isset($data['user_type']) || is_null($data['user_type'])) {
            $data['user_type'] = null;
        }
        if (!isset($data['unit_id']) || is_null($data['unit_id'])) {
            $data['unit_id'] = null;
        }
        $data['is_active'] = $data['is_active'] ?? false;

        // Update user
        $user->update($data);

        // Delete existing roles first
        $user->roles()->sync($roleIds);

        return redirect()->route('masterdata.admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete role mapping
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('masterdata.admin.users.index')->with('success', 'User berhasil dihapus');
    }

    /**
     * Assign role to user (legacy method for backward compatibility)
     */
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

