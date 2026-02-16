<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        // simple check: look up trx_user_role for user
        $has = \Illuminate\Support\Facades\DB::table('trx_user_role')
            ->where('user_id', $user->id)
            ->where('role_id', $role)
            ->exists();

        if (! $has) {
            abort(403);
        }

        return $next($request);
    }
}
