<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VerboseSessionDebug
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId();
        
        // Log incoming cookies
        $incomingCookies = $request->cookies->all();
        
        // Log incoming request details
        Log::info('SESSION_DEBUG_INCOMING', [
            'path' => $request->path(),
            'incoming_cookies' => array_keys($incomingCookies),
            'laravel_session_cookie' => $incomingCookies['XSRF-TOKEN'] ?? 'MISSING',
            'session_id_generated' => $sessionId,
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);
        
        // Log what's in the sessions table
        $allSessions = DB::table('sessions')->latest('last_activity')->limit(3)->get();
        foreach ($allSessions as $session) {
            $payload = unserialize(base64_decode($session->payload));
            Log::info('DB_SESSION_STATE', [
                'id' => substr($session->id, 0, 10) . '...',
                'user_id' => $session->user_id,
                'has_login' => isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d']),
            ]);
        }

        $response = $next($request);

        // Log response details and response headers
        Log::info('SESSION_DEBUG_OUTGOING', [
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'response_status' => $response->status(),
            'set_cookie_headers' => $response->headers->get('Set-Cookie'),
        ]);

        return $response;
    }
}
