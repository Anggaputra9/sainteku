<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthRateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'credential' => 'required|string',
            'password' => 'required|string',
        ]);

        $credential = trim($request->credential);

        if ($lockout = AuthRateLimiter::ensureLoginAllowed($request, $credential)) {
            return response()->json([
                'success' => false,
                'message' => $lockout['message'],
                'retry_after' => $lockout['retry_after'],
            ], $lockout['status']);
        }

        $user = User::where('email', $credential)
            ->orWhere('id', $credential)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            AuthRateLimiter::recordLoginFailure($request, $credential);

            return response()->json([
                'success' => false,
                'message' => __('messages.login_failed'),
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('messages.account_inactive') // GANTI
            ], 403);
        }

        AuthRateLimiter::clearLoginSuccess($request, $credential);
        Auth::login($user, $request->boolean('remember'));

        return response()->json([
            'success' => true,
            'message' => __('messages.login_success'), // GANTI
            'redirect' => '/dashboard'
        ]);
    }

    public function checkAuth()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
