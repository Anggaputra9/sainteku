<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogResponseHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Log ALL response headers before they're sent to the browser
        \Log::info('RESPONSE_HEADERS_LOGGED', [
            'path' => $request->path(),
            'status' => $response->status(),
            'headers' => $response->headers->all(),
            'cookie_header' => $response->headers->get('Set-Cookie'),
        ]);

        return $response;
    }
}
