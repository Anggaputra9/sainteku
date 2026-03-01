<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'credential' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->credential)
            ->orWhere('id', $request->credential)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email/ID atau password salah'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak aktif'
            ], 403);
        }

        Auth::login($user, $request->boolean('remember'));

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
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
