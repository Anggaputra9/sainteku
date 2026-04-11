<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

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
                $view->with('menus', collect());
                return;
            }

            $user = Auth::user();

            $isSuperAdmin = $user->roles()
                ->where('role_code', 'ADM')
                ->exists();

            // 🔥 ADMIN → tampil semua
            if ($isSuperAdmin) {
                $menus = Menu::whereNull('parent_id')
                    ->where('is_active', 1)
                    ->with([
                        'children' => function ($q) {
                            $q->where('is_active', 1)->orderBy('order_no');
                        }
                    ])
                    ->orderBy('order_no')
                    ->get();

                $view->with('menus', $menus);
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

            $menus = Menu::whereNull('parent_id')
                ->where('is_active', 1)
                ->where('id', '!=', 1) // ⬅️ HILANGKAN MASTER DATA
                ->where(function ($q) use ($allowedModulesRead) {
                    $q->whereNull('module_id')
                        ->orWhereIn('module_id', $allowedModulesRead);
                })
                ->with([
                    'children' => function ($q) use ($allowedModulesRead, $allowedModulesCreate, $allowedModulesApprove) {
                        $q->where('is_active', 1)
                            ->where(function ($q2) use ($allowedModulesRead, $allowedModulesCreate, $allowedModulesApprove) {
                                // Menu tanpa module_id (tidak butuh permission khusus)
                                $q2->orWhereNull('module_id')

                                // Menu dengan permission R (Read) - default untuk menu biasa
                                ->orWhere(function ($q3) use ($allowedModulesRead) {
                                    $q3->whereIn('module_id', $allowedModulesRead)
                                        ->whereNot(function ($subQ) {
                                            // Kecualikan menu yang butuh permission khusus
                                            $subQ->where('menu_link', 'like', '%review%')
                                                 ->orWhere('menu_link', 'like', '%pengajuan%')
                                                 ->orWhere('menu_link', 'like', '%persetujuan%');
                                        });
                                })

                                // Menu review dengan permission A (Approve)
                                ->orWhere(function ($q4) use ($allowedModulesApprove) {
                                    $q4->whereIn('module_id', $allowedModulesApprove)
                                        ->where('menu_link', 'like', '%review%');
                                })

                                // Menu pengajuan/peminjaman dengan permission C (Create)
                                ->orWhere(function ($q5) use ($allowedModulesCreate) {
                                    $q5->whereIn('module_id', $allowedModulesCreate)
                                        ->where(function ($subQ) {
                                            $subQ->where('menu_link', 'like', '%pengajuan%')
                                                 ->orWhere('menu_link', 'like', '%peminjaman%');
                                        });
                                })

                                // Menu persetujuan dengan permission A (Approve)
                                ->orWhere(function ($q6) use ($allowedModulesApprove) {
                                    $q6->whereIn('module_id', $allowedModulesApprove)
                                        ->where('menu_link', 'like', '%persetujuan%');
                                });
                            })
                            ->orderBy('order_no');
                    }
                ])
                ->orderBy('order_no')
                ->get();

            $view->with('menus', $menus);
        });
    }
}
