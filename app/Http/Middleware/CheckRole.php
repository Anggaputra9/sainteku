<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized - Silakan login terlebih dahulu.');
        }

        // Ambil role_code user
        $userRoles = \DB::table('trx_user_role')
            ->join('mst_role', 'trx_user_role.role_id', '=', 'mst_role.id')
            ->where('trx_user_role.user_id', $user->id)
            ->pluck('mst_role.role_code')
            ->toArray();

        // Cek apakah user punya role yang diizinkan
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        // Tambahan: admin super bisa akses semua
        if (in_array('ADM', $userRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access. Anda tidak memiliki izin.');
    }
}
