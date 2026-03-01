<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email
        $request->validate(['email' => 'required|email']);

        // Cek email di mst_user
        $user = DB::table('mst_user')->where('email', $request->email)->first();

        if (!$user) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan dalam sistem.'
                ]);
            }
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Generate token
        $token = Str::random(60);

        // Hapus token lama
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        try {
            // Kirim email
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau spam.'
                ]);
            }

            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim email. Error: ' . $e->getMessage()
                ]);
            }

            return back()->withErrors(['email' => 'Gagal mengirim email.']);
        }
    }
}
