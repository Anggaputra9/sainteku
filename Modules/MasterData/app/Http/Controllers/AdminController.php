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
        // Pake Eager Loading biar nggak kena N+1 Query (Udah bener ini)
        $query = User::with(['roles', 'unitUtama'])->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // --- TAMBAHAN: Logic Custom Pagination ---
        // Ambil request per_page, kalau kosong defaultnya 10
        $perPage = $request->input('per_page', 10);
        // Validasi biar user gak iseng masukin angka 1 juta di URL yang bikin server jebol
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $users = $query->paginate($perPage)->withQueryString();

        // --- AMBIL DATA MASTER UNTUK MODAL ---
        $roles = Role::where('is_active', '1')->get();

        // Saran: Kalau data unit ini ribuan, lebih baik dropdown unit di modal diubah 
        // pakai Select2 AJAX biar narik datanya pas diketik aja (nggak bikin berat browser).
        // Sementara kita pakai get() biasa dengan select kolom seperlunya biar ringan.
        $units = Unit::where('is_active', '1')->select('id', 'unit_name', 'unit_parent')->get();

        $userTypes = DB::table('ref_user_type')->get();

        return view('masterdata::admin.users', compact('users', 'roles', 'units', 'userTypes', 'perPage'));
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
        // 1. VALIDASI
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mst_user,email',
            'password' => 'required|string|min:8|confirmed',
            'identity_id' => 'nullable|string|max:20',
            'user_type' => 'required|string|exists:ref_user_type,id',

            // Validasi baru untuk Unit Utama & Tambahan
            'tingkatUtama' => 'required|string|in:kampus,fakultas,prodi',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'unit_tambahan' => 'nullable|array',
            'unit_tambahan.*' => 'string|exists:mst_unit,id',

            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:mst_role,id',
        ]);

        // Generate ID Custom
        $lastUser = User::orderBy('id', 'desc')->first();
        if ($lastUser) {
            $lastNumber = (int) substr($lastUser->id, 1);
            $newId = 'U' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'U0001';
        }

        // 2. EKSTRAK DATA YANG BUKAN KOLOM TABEL mst_user
        $roleIds = $data['role_ids'];
        $unitTambahan = $data['unit_tambahan'] ?? []; // Default array kosong kalau gak ada centangan
        $tingkatUtama = $data['tingkatUtama'];

        // Wajib di-unset biar gak error pas User::create()
        unset($data['role_ids'], $data['unit_tambahan'], $data['tingkatUtama']);

        // Set data sisa untuk tabel mst_user
        $data['id'] = $newId;
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->has('is_active') ? '1' : '0';
        $data['identity_id'] = $data['identity_id'] ?? null;

        // 3. EKSEKUSI PENYIMPANAN
        // A. Simpan Data User (sekaligus nyimpen unit_id utama)
        $user = User::create($data);

        // B. Simpan Hak Akses / Role
        $user->roles()->sync($roleIds);

        // --- TAMBAHIN BARIS INI SEMENTARA BUAT NGECEK ---
        //dd('Isi Tingkat Utama:', $tingkatUtama, 'Isi Unit Tambahan:', $unitTambahan);

        // C. Simpan Unit Tambahan dengan Logic Strict
        if ($tingkatUtama === 'kampus') {
            // Kalau levelnya kampus, bersihin unit tambahannya (jaga-jaga kalau ada data bocor)
            $user->unitTambahan()->sync([]);
        } else {
            // Kalau fakultas/prodi dan array-nya nggak kosong, masukin ke tabel pivot
            if (!empty($unitTambahan)) {
                $user->unitTambahan()->sync($unitTambahan);
            }
        }

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

            // Validasi Baru
            'tingkatUtama' => 'required|string|in:kampus,fakultas,prodi',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'unit_tambahan' => 'nullable|array',
            'unit_tambahan.*' => 'string|exists:mst_unit,id',

            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:mst_role,id',
        ]);

        $roleIds = $data['role_ids'];
        $unitTambahan = $data['unit_tambahan'] ?? [];
        $tingkatUtama = $data['tingkatUtama'];

        unset($data['role_ids'], $data['unit_tambahan'], $data['tingkatUtama']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $isSuicideAttempt = false;

        if ($id === auth()->id() && !$request->has('is_active')) {
            $data['is_active'] = '1';
            $isSuicideAttempt = true;
        } else {
            $data['is_active'] = $request->has('is_active') ? '1' : '0';
        }

        $data['identity_id'] = $data['identity_id'] ?? null;
        $data['user_type'] = $data['user_type'] ?? null;

        // Eksekusi Update Tabel mst_user
        $user->update($data);

        // Sync Roles
        $user->roles()->sync($roleIds);

        // Eksekusi Update Unit Tambahan (Pakai Query Builder biar anti error String ID)
        \Illuminate\Support\Facades\DB::table('mst_user_unit')
            ->where('user_id', $user->id)
            ->delete(); // Hapus yang lama dulu

        if ($tingkatUtama !== 'kampus' && !empty($unitTambahan)) {
            $pivotData = [];
            foreach ($unitTambahan as $uId) {
                $pivotData[] = [
                    'user_id' => $user->id,
                    'unit_id' => $uId
                ];
            }
            \Illuminate\Support\Facades\DB::table('mst_user_unit')->insert($pivotData);
        }

        if ($isSuicideAttempt) {
            return redirect('/masterdata/admin/users')->with('error', 'Bahaya! Anda tidak diperbolehkan menonaktifkan akun sendiri.');
        }

        return redirect('/masterdata/admin/users')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
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