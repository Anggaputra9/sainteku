<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Menampilkan form reset password
     */
    public function showResetForm($token)
    {
        // Cek token
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$reset) {
            return redirect('/')->with('error', 'Token tidak valid.');
        }

        // Tampilkan halaman reset password
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $reset->email
        ]);
    }

    /**
     * Memproses reset password
     */
    public function reset(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // Cek token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek kadaluarsa
        if (Carbon::parse($reset->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kadaluarsa.']);
        }

        // Update password di mst_user
        DB::table('mst_user')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
