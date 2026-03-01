<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class ValidateSignature
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasValidSignature()) {
            return $next($request);
        }

        throw new InvalidSignatureException;
    }
}
