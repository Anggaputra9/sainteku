<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\app\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        // Ambil parameter dari query string
        $search = $request->input('search', '');
        $perPage = max(1, $request->input('per_page', 10));

        // Query data roles dengan search filter
        $rolesQuery = Role::query();

        // Terapkan filter search (cari di role_code dan role_name)
        if (!empty($search)) {
            $rolesQuery->where(function ($query) use ($search) {
                $query->where('role_code', 'like', '%' . $search . '%')
                    ->orWhere('role_name', 'like', '%' . $search . '%');
            });
        }

        $roles = $rolesQuery->orderBy('id')->paginate($perPage);

        // Ambil data referensi untuk matriks
        $modules = DB::table('mst_module')->orderBy('id')->get();
        $permissions = DB::table('ref_permission')->orderBy('id')->get();

        // Ambil semua izin yang sudah ada, kelompokkan berdasarkan role_id
        $rolePermissions = DB::table('trx_role_permission')
            ->where('allowed', 1)
            ->get()
            ->groupBy('role_id');

        return view('masterdata::roles.index', compact('roles', 'modules', 'permissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_code' => 'required|string|max:5|unique:mst_role,role_code',
            'role_name' => 'required|string|max:30',
        ]);

        Role::create([
            'role_code' => strtoupper($request->role_code),
            'role_name' => $request->role_name,
            'is_active' => $request->has('is_active') ? '1' : '0',
        ]);

        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Role baru berhasil ditambahkan!');
    }

    // =================================================================
    // FUNGSI KHUSUS UNTUK MENYIMPAN EDIT NAMA & KODE ROLE
    // =================================================================
    public function update(Request $request, $id)
    {
        // 1. Validasi data
        $request->validate([
            'role_code' => 'required|string|max:5|unique:mst_role,role_code,' . $id,
            'role_name' => 'required|string|max:30',
        ]);

        // 2. Cari data & Update
        $role = Role::findOrFail($id);
        $role->update([
            'role_code' => strtoupper($request->role_code),
            'role_name' => $request->role_name,
            'is_active' => $request->has('is_active') ? '1' : '0',
        ]);

        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Data Role ' . $role->role_name . ' berhasil diperbarui!');
    }


    // =================================================================
    // FUNGSI KHUSUS UNTUK MENYIMPAN MATRIKS HAK AKSES (PERMISSIONS)
    // =================================================================
    public function updatePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        // Ambil array permissions dari form (Matriks)
        $permissions = $request->input('permissions', []);

        $insertData = [];

        // Looping data
        foreach ($permissions as $modulId => $permIds) {
            foreach ($permIds as $permId) {
                $insertData[] = [
                    'role_id'       => $roleId, // Disini $roleId valid karena parameter fungsinya $roleId
                    'modul_id'      => $modulId,
                    'permission_id' => $permId,
                    'allowed'       => 1,
                ];
            }
        }

        // Transaksi DB
        DB::transaction(function () use ($roleId, $insertData) {
            DB::table('trx_role_permission')->where('role_id', $roleId)->delete();

            if (!empty($insertData)) {
                DB::table('trx_role_permission')->insert($insertData);
            }
        });

        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Hak akses untuk role ' . $role->role_name . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $namaRole = $role->role_name; // Simpan nama dulu untuk dimunculkan di pesan sukses

        // Hapus data dari tabel mst_role
        $role->delete();

        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Role ' . $namaRole . ' berhasil dihapus dari sistem!');
    }
}
