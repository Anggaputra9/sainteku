<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DebugSession
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('DEBUG_SESSION: Before request', [
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'session_exists' => session()->has('_token'),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'login_guard' => session('auth.guard'),
            'all_session_keys' => array_keys(session()->all()),
        ]);

        $response = $next($request);

        Log::info('DEBUG_SESSION: After request', [
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'session_exists' => session()->has('_token'),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'login_guard' => session('auth.guard'),
            'all_session_keys' => array_keys(session()->all()),
        ]);

        return $response;
    }
}
