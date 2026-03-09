<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roleId)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // Cek apakah user punya role yang sesuai
        if (!in_array(Auth::user()->user_type, ['001','002', '003', '004'])) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
