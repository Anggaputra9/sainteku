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
use Modules\MasterData\Support\BulkUserImportService;
use Modules\MasterData\Support\UserIdService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public const PRIMARY_ADMIN_ID = 'ADM-UIN-0000001';

    public const STUDENT_USER_TYPE = 'MHS';

    public function index()
    {
        $roles = Role::where('is_active', '1')->get();
        $units = Unit::where('is_active', '1')->select('id', 'unit_name', 'unit_parent')->get();
        $userTypes = DB::table('ref_user_type')->get();

        return view('masterdata::admin.users', compact('roles', 'units', 'userTypes'))->with('title', 'Daftar User');
    }

    public function getUsersData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $users = $this->buildUsersQuery($request)
            ->paginate($perPage)
            ->through(fn (User $user): array => $this->formatUserForApi($user));

        return response()->json($users)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildUsersQuery(Request $request)
    {
        $query = User::with(['roles', 'unitUtama', 'unitTambahan']);

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('unit_id', 'like', '%' . $search . '%')
                    ->orWhereHas('roles', function ($rq) use ($search) {
                        $rq->where('role_name', 'like', '%' . $search . '%')
                            ->orWhere('role_code', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('unitUtama', function ($uq) use ($search) {
                        $uq->where('unit_name', 'like', '%' . $search . '%')
                            ->orWhere('id', 'like', '%' . $search . '%');
                    });
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['1', '0'], true)) {
            $query->where('is_active', $status);
        }

        $roleId = $request->query('role');
        if ($roleId !== null && $roleId !== '' && is_numeric($roleId)) {
            $query->whereHas('roles', fn ($rq) => $rq->where('mst_role.id', (int) $roleId));
        }

        $unitId = trim((string) $request->query('unit', ''));
        if ($unitId !== '') {
            $query->where('unit_id', $unitId);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        return $query;
    }

    private function formatUserForApi(User $user): array
    {
        $tingkatUtama = 'kampus';
        if ($user->unitUtama) {
            if ((int) $user->unitUtama->unit_type_id === 2) {
                $tingkatUtama = 'fakultas';
            } elseif ((int) $user->unitUtama->unit_type_id === 3) {
                $tingkatUtama = 'prodi';
            }
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'identity_id' => $user->identity_id,
            'user_type' => $user->user_type,
            'unit_id' => $user->unit_id,
            'unit_name' => $user->unitUtama?->unit_name,
            'is_active' => $user->is_active,
            'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'role_name' => $role->role_name,
            ])->values()->all(),
            'role_ids' => $user->roles->pluck('id')->values()->all(),
            'unit_tambahan' => $user->unitTambahan->pluck('id')->values()->all(),
            'tingkat_utama' => $tingkatUtama,
            'update_url' => route('masterdata.admin.users.update', $user->id),
            'delete_url' => route('masterdata.admin.users.destroy', $user->id),
            'is_admin' => $this->userIsAdmin($user),
            'is_primary_admin' => $user->id === self::PRIMARY_ADMIN_ID,
            'can_delete' => $this->canDeleteUser($user),
        ];
    }

    private function getAdminRoleId(): ?int
    {
        return Role::where('role_code', 'ADM')->value('id');
    }

    private function getMahasiswaRoleId(): ?int
    {
        return Role::where('role_code', 'MHS')->value('id');
    }

    private function applyMahasiswaRules(string $userType, array &$roleIds, array &$unitTambahan): void
    {
        if ($userType !== self::STUDENT_USER_TYPE) {
            return;
        }

        $mahasiswaRoleId = $this->getMahasiswaRoleId();
        if ($mahasiswaRoleId) {
            $roleIds = [$mahasiswaRoleId];
        }

        $unitTambahan = [];
    }

    private function countActiveAdmins(): int
    {
        $adminRoleId = $this->getAdminRoleId();
        if (! $adminRoleId) {
            return 0;
        }

        return User::where('is_active', '1')
            ->whereHas('roles', fn ($q) => $q->where('mst_role.id', $adminRoleId))
            ->count();
    }

    private function userIsAdmin(User $user): bool
    {
        $adminRoleId = $this->getAdminRoleId();
        if (! $adminRoleId) {
            return false;
        }

        return $user->roles()->where('mst_role.id', $adminRoleId)->exists();
    }

    private function canDeleteUser(User $user): bool
    {
        if ($user->id === self::PRIMARY_ADMIN_ID) {
            return false;
        }

        if (! $this->userIsAdmin($user)) {
            return true;
        }

        return $this->countActiveAdmins() > 1;
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::orderBy('id')->get();
        return view('masterdata::admin.create', compact('roles'))->with('title', 'Tambah User Baru');
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

        // 2. EKSTRAK DATA YANG BUKAN KOLOM TABEL mst_user
        $roleIds = $data['role_ids'];
        $unitTambahan = $data['unit_tambahan'] ?? [];
        $tingkatUtama = $data['tingkatUtama'];

        $this->applyMahasiswaRules($data['user_type'], $roleIds, $unitTambahan);

        $newId = app(UserIdService::class)->assignIdForNewUser($roleIds, $data['unit_id']);

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
            ->with('success', 'User berhasil ditambahkan.');
    }
    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('id')->get();
        $userRoles = DB::table('trx_user_role')->where('user_id', $id)->pluck('role_id')->toArray();

        return view('masterdata::admin.edit', compact('user', 'roles', 'userRoles'))->with('title', 'Edit User');
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

        $this->applyMahasiswaRules($data['user_type'] ?? '', $roleIds, $unitTambahan);

        $adminRoleId = $this->getAdminRoleId();
        $hadAdminRole = $adminRoleId && $user->roles()->where('mst_role.id', $adminRoleId)->exists();
        $hasAdminRole = $adminRoleId && in_array($adminRoleId, $roleIds, true);

        if ($hadAdminRole && ! $hasAdminRole) {
            if ($user->id === self::PRIMARY_ADMIN_ID) {
                return redirect('/masterdata/admin/users')
                    ->with('error', 'Administrator utama tidak dapat diturunkan jabatannya.');
            }

            if ($this->countActiveAdmins() <= 1) {
                return redirect('/masterdata/admin/users')
                    ->with('error', 'Tidak dapat menurunkan jabatan administrator terakhir yang tersisa.');
            }
        }

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

        $newId = app(UserIdService::class)->reassignIfNeeded($user, $roleIds, $data['unit_id']);
        if ($newId !== null) {
            $user = User::findOrFail($newId);
        }

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

        if (! $this->canDeleteUser($user)) {
            return redirect()->route('masterdata.admin.users.index')
                ->with('error', 'Administrator ini tidak dapat dihapus karena merupakan administrator terakhir yang tersisa.');
        }

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

        $user = User::findOrFail($userId);
        $roleIds = DB::table('trx_user_role')->where('user_id', $userId)->pluck('role_id')->all();
        $newId = app(UserIdService::class)->reassignIfNeeded($user, $roleIds, $user->unit_id);

        return redirect()->route('masterdata.admin.users.index')->with('success', 'Peran berhasil diperbarui.');
    }

    public function bulkStore(Request $request, BulkUserImportService $importService)
    {
        $data = $request->validate([
            'user_type' => 'required|string|exists:ref_user_type,id',
            'tingkatUtama' => 'required|string|in:kampus,fakultas,prodi',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:mst_role,id',
            'is_active' => 'nullable|boolean',
            'bulk_text' => 'required|string',
        ]);

        $roleIds = $data['role_ids'] ?? [];
        if ($data['user_type'] !== self::STUDENT_USER_TYPE && count($roleIds) < 1) {
            return response()->json([
                'message' => 'Pilih minimal satu hak akses / role.',
            ], 422);
        }

        $result = $importService->import(
            $data['user_type'],
            $data['unit_id'],
            $roleIds,
            (bool) ($data['is_active'] ?? true),
            $data['bulk_text'],
        );

        $message = $result['success_count'] . ' user berhasil ditambahkan';
        if ($result['failed_count'] > 0) {
            $message .= ', ' . $result['failed_count'] . ' gagal';
        }

        return response()->json([
            ...$result,
            'message' => $message,
        ]);
    }

    public function downloadBulkTemplate(Request $request): StreamedResponse
    {
        $userType = $request->query('type', self::STUDENT_USER_TYPE);
        $isStudent = $userType === self::STUDENT_USER_TYPE;

        $filename = $isStudent ? 'template-bulk-mahasiswa.csv' : 'template-bulk-staff.csv';

        return response()->streamDownload(function () use ($isStudent): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            if ($isStudent) {
                fputcsv($handle, ['nama', 'nim']);
                fputcsv($handle, ['Angga Wicaksono', '123456789']);
                fputcsv($handle, ['Rizal Fakhri Nur Riski', '0987654321']);
            } else {
                fputcsv($handle, ['nama', 'nip', 'email']);
                fputcsv($handle, ['Arifian Ilham', '19900101001', 'arifian@uinsaizu.ac.id']);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}