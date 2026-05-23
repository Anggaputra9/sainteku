<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email user
     * dengan menggunakan EmailSetting aktif (default) yang dikelola admin.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('mst_user')->where('email', $request->email)->first();

        if (!$user) {
            return $this->respond($request, false, 'Email tidak ditemukan dalam sistem.');
        }

        // Generate token & simpan
        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        // Kirim email pakai konfigurasi email aktif di database (EmailSetting default)
        try {
            $result = MailService::sendPasswordReset($request->email, $token);

            if (!$result['success']) {
                Log::warning('Reset password mail gagal: ' . $result['message']);
                return $this->respond($request, false, 'Gagal mengirim email reset password. ' . $result['message']);
            }

            return $this->respond($request, true, 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Throwable $e) {
            Log::error('Reset password mail exception: ' . $e->getMessage());
            return $this->respond($request, false, 'Gagal mengirim email. Error: ' . $e->getMessage());
        }
    }

    private function respond(Request $request, bool $success, string $message)
    {
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        if ($success) {
            return back()->with('status', $message);
        }
        return back()->withErrors(['email' => $message]);
    }
}
