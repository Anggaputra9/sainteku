<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        \Log::info('LOGIN: Starting login process');
        
        $validated = $request->validate([
            'credential' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by email OR id
        $user = User::where('email', $validated['credential'])
                    ->orWhere('id', $validated['credential'])
                    ->first();

        // User not found
        if (!$user) {
            \Log::warning('LOGIN: User not found', ['credential' => $validated['credential']]);
            return back()
                ->withErrors(['credential' => 'Email/ID tidak ditemukan'])
                ->withInput($request->only('credential'))
                ->with('show_login_modal', true);
        }

        // Password incorrect
        if (!Hash::check($validated['password'], $user->password)) {
            \Log::warning('LOGIN: Password incorrect', ['user_id' => $user->id]);
            return back()
                ->withErrors(['credential' => 'Password salah'])
                ->withInput($request->only('credential'))
                ->with('show_login_modal', true);
        }

        // Login successful
        \Log::info('LOGIN: Password verified', ['user_id' => $user->id, 'auth_check_before' => Auth::check()]);
        
        Auth::login($user, $request->boolean('remember'));
        
        \Log::info('LOGIN: Auth::login() called', ['auth_check_after' => Auth::check()]);
        
        $request->session()->regenerate();
        
        \Log::info('LOGIN: Session regenerated', [
            'session_id' => session()->getId(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

