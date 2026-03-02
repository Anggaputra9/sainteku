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

            $allowedModuleIds = DB::table('trx_role_permission as rp')
                ->join('ref_permission as p', 'rp.permission_id', '=', 'p.id')
                ->whereIn('rp.role_id', $roleIds)
                ->where('rp.allowed', 1)
                ->where('p.permission_code', 'R')
                ->pluck('rp.modul_id')
                ->unique()
                ->toArray();

            $menus = Menu::whereNull('parent_id')
                ->where('is_active', 1)
                ->where('id', '!=', 1) // ⬅️ HILANGKAN MASTER DATA
                ->where(function ($q) use ($allowedModuleIds) {
                    $q->whereNull('module_id')
                        ->orWhereIn('module_id', $allowedModuleIds);
                })
                ->with([
                    'children' => function ($q) use ($allowedModuleIds) {
                        $q->where('is_active', 1)
                            ->where(function ($q2) use ($allowedModuleIds) {
                                $q2->whereNull('module_id')
                                    ->orWhereIn('module_id', $allowedModuleIds);
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
