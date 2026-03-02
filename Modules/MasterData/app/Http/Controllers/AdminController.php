<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\MasterData\app\Models\User;
use Modules\MasterData\app\Models\Role;
use Modules\MasterData\app\Models\Unit;      
use Modules\MasterData\app\Models\UserType;

class AdminController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'unit'])->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10)->withQueryString();

        // --- AMBIL DATA MASTER UNTUK MODAL ---
        $roles = Role::where('is_active', '1')->get();
        $units = Unit::where('is_active', '1')->get(); // Data untuk dropdown Unit
        $userTypes = DB::table('ref_user_type')->get();

        return view('masterdata::admin.users', compact('users', 'roles', 'units', 'userTypes'));
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
        // 1. Validasi Input (HAPUS validasi 'id' karena kita akan generate otomatis)
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:mst_user,email',
            'password'    => 'required|string|min:8|confirmed',
            'identity_id' => 'nullable|string|max:20',

            // Pastikan input user_type & unit_id benar-benar ada di tabel referensi
            'user_type'   => 'required|string|exists:ref_user_type,id',
            'unit_id'     => 'required|string|exists:mst_unit,id',

            'role_ids'    => 'required|array|min:1',
            'role_ids.*'  => 'integer|exists:mst_role,id',
        ]);

        // 2. Generate ID User Otomatis (Format: u0001, u0002, dst)
        $lastUser = User::orderBy('id', 'desc')->first();
        if ($lastUser) {
            // Ambil angka dari ID terakhir (misal 'u0001' jadi 1), tambah 1, lalu pad dengan 0
            $lastNumber = (int) substr($lastUser->id, 1);
            $newId = 'u' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'u0001';
        }

        // 3. Ekstrak role_ids agar tidak ikut tersimpan ke tabel mst_user
        $roleIds = $data['role_ids'];
        unset($data['role_ids']);

        // 4. Siapkan sisa data
        $data['id'] = $newId; // Masukkan ID yang sudah digenerate
        $data['password'] = Hash::make($data['password']); // Hash password

        // Sesuaikan tipe ENUM di database ('1' atau '0')
        $data['is_active'] = $request->has('is_active') ? '1' : '0';
        $data['identity_id'] = $data['identity_id'] ?? null;

        // 5. Simpan ke tabel mst_user
        $user = User::create($data);

        // 6. Assign roles ke tabel trx_user_role
        $user->roles()->sync($roleIds);

        return redirect()->route('masterdata.admin.users.index')
            ->with('success', 'User berhasil ditambahkan dengan ID: ' . $newId);
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
