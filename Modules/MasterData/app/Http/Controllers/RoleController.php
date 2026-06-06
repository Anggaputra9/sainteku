<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\app\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function index()
    {
        $modules = DB::table('mst_module')->orderBy('id')->get();
        $permissions = DB::table('ref_permission')->orderBy('id')->get();

        return view('masterdata::roles.index', compact('modules', 'permissions'))
            ->with('title', 'Daftar Role & Hak Akses');
    }

    public function getRolesData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $rolePermissions = DB::table('trx_role_permission')
            ->where('allowed', 1)
            ->get()
            ->groupBy('role_id');

        $userCounts = DB::table('trx_user_role')
            ->select('role_id', DB::raw('count(*) as cnt'))
            ->groupBy('role_id')
            ->pluck('cnt', 'role_id');

        $roles = $this->buildRolesQuery($request)
            ->paginate($perPage)
            ->through(fn (Role $role): array => $this->formatRoleForApi($role, $rolePermissions, $userCounts));

        return response()->json($roles)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildRolesQuery(Request $request)
    {
        $query = Role::query();

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('role_code', 'like', '%' . $search . '%')
                    ->orWhere('role_name', 'like', '%' . $search . '%');
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['1', '0'], true)) {
            $query->where('is_active', $status);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'name_asc' => $query->orderBy('role_name'),
            'name_desc' => $query->orderByDesc('role_name'),
            'code_asc' => $query->orderBy('role_code'),
            'code_desc' => $query->orderByDesc('role_code'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        return $query;
    }

    private function formatRoleForApi(Role $role, $rolePermissions, $userCounts): array
    {
        $perms = $rolePermissions->get($role->id, collect());
        $assigned = $perms
            ->map(fn ($p) => $p->modul_id . '-' . $p->permission_id)
            ->values()
            ->all();

        $userCount = (int) ($userCounts[$role->id] ?? 0);

        return [
            'id' => $role->id,
            'role_code' => $role->role_code,
            'role_name' => $role->role_name,
            'is_active' => $role->is_active,
            'initial' => mb_strtoupper(mb_substr($role->role_code, 0, 1)),
            'permission_count' => count($assigned),
            'user_count' => $userCount,
            'assigned_permissions' => $assigned,
            'update_url' => route('masterdata.roles.update', $role->id),
            'delete_url' => route('masterdata.roles.destroy', $role->id),
            'permissions_url' => route('masterdata.roles.permissions.update', $role->id),
            'can_delete' => $userCount === 0,
        ];
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
        $userCount = DB::table('trx_user_role')->where('role_id', $id)->count();

        if ($userCount > 0) {
            return redirect()->route('masterdata.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh ' . $userCount . ' user.');
        }

        $namaRole = $role->role_name;

        DB::table('trx_role_permission')->where('role_id', $id)->delete();
        $role->delete();

        return redirect()->route('masterdata.roles.index')
            ->with('success', 'Role ' . $namaRole . ' berhasil dihapus dari sistem!');
    }
}
