<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role = null)
    {
        // 1. Pastikan user udah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil semua role_code milik user yang lagi login
        $userRoles = DB::table('trx_user_role')
            ->join('mst_role', 'trx_user_role.role_id', '=', 'mst_role.id')
            ->where('trx_user_role.user_id', Auth::id())
            ->pluck('mst_role.role_code')
            ->toArray();

        // =========================================================
        // 🛡️ PENJAGAAN KHUSUS UNTUK URL MASTER DATA 🛡️
        // =========================================================
        // Sesuaikan parameter is() dengan prefix URL master data lu
        if ($request->is('masterdata*') || $request->is('*/masterdata*')) {
            if (!in_array('ADM', $userRoles) && !in_array('Administrator', $userRoles)) {
                abort(403, 'Akses Ditolak: Halaman Master Data hanya untuk Administrator.');
            }
        }

        // =========================================================
        // 🌟 JALUR VIP: BYPASS UNTUK ADMIN 🌟
        // =========================================================
        // Kalau user punya role 'ADM' atau 'Administrator', langsung bukain pintu!
        if (in_array('ADM', $userRoles) || in_array('Administrator', $userRoles)) {
            return $next($request);
        }

        // 3. Pengecekan normal untuk rakyat jelata (Non-Admin)
        if ($role) {
            $allowedRoles = explode('|', $role);
            $hasAccess = count(array_intersect($userRoles, $allowedRoles)) > 0;

            if (!$hasAccess) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk halaman ini.');
            }
        }

        // 4. Lolos sensor
        return $next($request);
    }
}