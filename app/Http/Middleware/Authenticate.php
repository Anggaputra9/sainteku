<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Set a session message to trigger modal on landing page
        if ($request->expectsJson()) {
            return null;
        }
        
        // Store the intended URL and set a session flag
        session()->flash('show_login_modal', true);
        session()->flash('login_message', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        
        // Redirect to landing page (where login modal is)
        return '/';
    }
}

