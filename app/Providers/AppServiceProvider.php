<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {

            if (!Auth::check()) {
                $view->with('sidebarMenus', collect());
                return;
            }

            $user = Auth::user();

            $isSuperAdmin = $user->roles()
                ->where('role_code', 'ADM')
                ->exists();

            // 🔥 ADMIN → tampil semua
            if ($isSuperAdmin) {
                $sidebarMenus = Menu::whereNull('parent_id')
                    ->where('is_active', 1)
                    ->with([
                        'children' => function ($q) {
                            $q->where('is_active', 1)->orderBy('order_no');
                        }
                    ])
                    ->orderBy('order_no')
                    ->get();

                $view->with('sidebarMenus', $sidebarMenus);
                return;
            }

            // 🔥 NON ADMIN → filter Master Data (id = 1)
            $roleIds = $user->roles->pluck('id')->toArray();

            // Get allowed modules for different permissions
            $allowedModulesRead = DB::table('trx_role_permission as rp')
                ->join('ref_permission as p', 'rp.permission_id', '=', 'p.id')
                ->whereIn('rp.role_id', $roleIds)
                ->where('rp.allowed', 1)
                ->where('p.permission_code', 'R')
                ->pluck('rp.modul_id')
                ->unique()
                ->toArray();

            $allowedModulesCreate = DB::table('trx_role_permission as rp')
                ->join('ref_permission as p', 'rp.permission_id', '=', 'p.id')
                ->whereIn('rp.role_id', $roleIds)
                ->where('rp.allowed', 1)
                ->where('p.permission_code', 'C')
                ->pluck('rp.modul_id')
                ->unique()
                ->toArray();

            $allowedModulesApprove = DB::table('trx_role_permission as rp')
                ->join('ref_permission as p', 'rp.permission_id', '=', 'p.id')
                ->whereIn('rp.role_id', $roleIds)
                ->where('rp.allowed', 1)
                ->where('p.permission_code', 'A')
                ->pluck('rp.modul_id')
                ->unique()
                ->toArray();

            // ============ Filter sidebar untuk non-admin ============
            //
            // Aturan visibility parent menu:
            //  - Parent yang TIDAK punya child (mis. Dashboard, root link
            //    langsung) → tampil bila module_id null atau ada di
            //    allowedModulesRead. Tetap respek module_id-nya.
            //  - Parent yang PUNYA child → tampil selama minimal 1 child
            //    yang accessible. Kita TIDAK lagi men-filter parent
            //    berdasarkan module_id-nya sendiri, supaya kasus seperti
            //    "Monev Akademik" (module_id=3) tetap muncul untuk user
            //    yang cuma punya akses ke child seperti Ujian (module_id
            //    null) walau tidak punya permission Monev Akademik.
            //
            // Item yang khusus admin (Master Data, Manajemen Menu,
            // Pengaturan Aplikasi) tetap di-exclude di awal.
            $sidebarMenus = Menu::whereNull('parent_id')
                ->where('is_active', 1)
                ->whereNotIn('id', [1, 101, 200])
                ->with([
                    'children' => function ($q) use ($allowedModulesRead, $allowedModulesCreate, $allowedModulesApprove) {
                        $q->where('is_active', 1)
                            ->where(function ($q2) use ($allowedModulesRead, $allowedModulesCreate, $allowedModulesApprove) {
                                // Menu tanpa module_id (bebas permission)
                                $q2->whereNull('module_id')

                                // Menu Read default - kecuali yang butuh permission khusus
                                ->orWhere(function ($q3) use ($allowedModulesRead) {
                                    $q3->whereIn('module_id', $allowedModulesRead)
                                        ->whereNot(function ($subQ) {
                                            $subQ->where('menu_link', 'like', '%review%')
                                                 ->orWhere('menu_link', 'like', '%pengajuan%')
                                                 ->orWhere('menu_link', 'like', '%persetujuan%');
                                        });
                                })

                                // Review menu - butuh Approve
                                ->orWhere(function ($q4) use ($allowedModulesApprove) {
                                    $q4->whereIn('module_id', $allowedModulesApprove)
                                        ->where('menu_link', 'like', '%review%');
                                })

                                // Pengajuan / peminjaman - butuh Create
                                ->orWhere(function ($q5) use ($allowedModulesCreate) {
                                    $q5->whereIn('module_id', $allowedModulesCreate)
                                        ->where(function ($subQ) {
                                            $subQ->where('menu_link', 'like', '%pengajuan%')
                                                 ->orWhere('menu_link', 'like', '%peminjaman%');
                                        });
                                })

                                // Persetujuan - butuh Approve
                                ->orWhere(function ($q6) use ($allowedModulesApprove) {
                                    $q6->whereIn('module_id', $allowedModulesApprove)
                                        ->where('menu_link', 'like', '%persetujuan%');
                                });
                            })
                            ->orderBy('order_no');
                    }
                ])
                ->orderBy('order_no')
                ->get()
                // Parent harus tetap kelihatan kalau punya child accessible.
                // Untuk parent yang memang berdiri sendiri (no children),
                // kembali ke aturan module_id-nya: null atau termasuk
                // allowedModulesRead.
                ->filter(function ($menu) use ($allowedModulesRead) {
                    if ($menu->children->isNotEmpty()) {
                        return true;
                    }
                    return is_null($menu->module_id)
                        || in_array($menu->module_id, $allowedModulesRead, true);
                })
                ->values();

            $view->with('sidebarMenus', $sidebarMenus);
        });
    }
}
