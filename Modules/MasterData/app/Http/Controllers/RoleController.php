<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\app\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id')->get();
        return view('masterdata::roles.index', compact('roles'));
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

    public function update(Request $request, $id)
    {
        // 1. Validasi data yang dikirim dari form Edit
        $request->validate([
            // unique:mst_role,role_code, . $id -> Artinya kode role tidak boleh sama dengan yang lain, 
            // KECUALI dengan kode miliknya sendiri (saat ini)
            'role_code' => 'required|string|max:5|unique:mst_role,role_code,' . $id,
            'role_name' => 'required|string|max:30',
        ]);

        // 2. Cari data Role berdasarkan ID
        $role = Role::findOrFail($id);

        // 3. Update datanya
        $role->update([
            'role_code' => strtoupper($request->role_code), // Pastikan selalu huruf besar
            'role_name' => $request->role_name,
            'is_active' => $request->has('is_active') ? '1' : '0', // Ubah ke format ENUM database
        ]);

        // 4. Kembalikan ke halaman index dengan pesan sukses
        // (Sesuaikan nama route redirect ini dengan nama route index kamu)
        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Data Role ' . $role->role_name . ' berhasil diperbarui!');
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
