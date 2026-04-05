<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Ambil nama Route yang sedang diakses (Contoh: 'monevakademik.tashih.index')
        $currentRoute = Route::currentRouteName();

        // Jika route tidak punya nama, biarkan lewat (atau sesuaikan dengan SOP keamananmu)
        if (!$currentRoute) {
            return $next($request);
        }

        // Opsional: Bypass mutlak untuk Super Admin (misal user_type '001' adalah Super Admin)
        if (Auth::user()->user_type === '001') {
            return $next($request);
        }

        // 3. Cek apakah route ini terdaftar di tabel mst_menu
        $menu = DB::table('mst_menu')->where('menu_link', $currentRoute)->first();

        // Jika rute tidak ada di mst_menu, kita anggap itu rute umum/bebas (misal halaman profile, logout)
        if (!$menu) {
            return $next($request);
        }

        // 4. KUNCI UTAMA: Cek hak akses ke database!
        // Nyari kecocokan antara User -> trx_user_role -> trx_role_menu -> mst_menu
        $hasAccess = DB::table('trx_role_menu')
            ->join('trx_user_role', 'trx_role_menu.role_id', '=', 'trx_user_role.role_id')
            ->where('trx_user_role.user_id', Auth::id())
            ->where('trx_role_menu.menu_id', $menu->id)
            ->exists();

        // 5. Eksekusi
        if (!$hasAccess) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}