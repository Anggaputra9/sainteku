<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DetailedSessionDebug
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId();
        
        // Log incoming request details
        Log::info('DETAILED_DEBUG: Incoming request', [
            'path' => $request->path(),
            'session_id' => $sessionId,
            'auth_check_before' => Auth::check(),
            'auth_id_before' => Auth::id(),
            'session_keys_before' => array_keys(session()->all()),
            'request_cookies' => array_keys($request->cookies->all()),
            'laravel_session_cookie' => $request->cookie('XSRF-TOKEN') ? 'present' : 'missing',
        ]);
        
        // Check what's actually in the database session
        $dbSession = DB::table('sessions')->find($sessionId);
        if ($dbSession) {
            $payload = unserialize(base64_decode($dbSession->payload));
            Log::info('DETAILED_DEBUG: DB Session Content', [
                'session_id' => $sessionId,
                'db_user_id' => $dbSession->user_id,
                'payload_keys' => array_keys($payload),
                'has_login_key' => isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d']),
                'login_data' => isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d']) ? 
                    $payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'] : 'NOT FOUND',
            ]);
        } else {
            Log::warning('DETAILED_DEBUG: DB Session not found', ['session_id' => $sessionId]);
        }

        $response = $next($request);

        // Log response details
        Log::info('DETAILED_DEBUG: After request processing', [
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'auth_check_after' => Auth::check(),
            'auth_id_after' => Auth::id(),
            'session_keys_after' => array_keys(session()->all()),
        ]);

        return $response;
    }
}
