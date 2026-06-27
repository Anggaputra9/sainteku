<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuthRateLimiter;
use App\Services\MailService;
use App\Services\WhatsappService;
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
     * Kirim link reset password ke email user (+ WhatsApp jika ada nomor).
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'credential' => ['required', 'string', 'max:255'],
        ]);

        $credential = trim($request->credential);

        if ($lockout = AuthRateLimiter::ensureForgotPasswordAllowed($request, $credential)) {
            return $this->respond(
                $request,
                false,
                $lockout['message'],
                $lockout['status'],
                $lockout['retry_after'],
            );
        }

        AuthRateLimiter::recordForgotPasswordAttempt($request, $credential);

        $user = $this->resolveUser($credential);

        if (!$user) {
            return $this->respond($request, false, __('messages.account_not_found'));
        }

        if (!($user->is_active ?? false)) {
            return $this->respond($request, false, __('messages.account_inactive'));
        }

        if (empty($user->email)) {
            return $this->respond($request, false, 'Akun tidak memiliki email terdaftar. Hubungi administrator.');
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        try {
            $result = MailService::sendPasswordReset($user->email, $token);

            if (!$result['success']) {
                Log::warning('Reset password mail gagal: ' . $result['message']);
                return $this->respond($request, false, 'Gagal mengirim email reset password. ' . $result['message']);
            }

            $waSent = false;
            if (!empty($user->phone_number)) {
                try {
                    $waSent = app(WhatsappService::class)->sendPasswordReset(
                        $user->phone_number,
                        $token,
                        $user->name ?? ''
                    );
                } catch (\Throwable $e) {
                    Log::warning('Reset password WA gagal: ' . $e->getMessage());
                }
            }

            $message = 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.';
            if ($waSent) {
                $message .= ' Link juga telah dikirim ke WhatsApp Anda.';
            }

            return $this->respond($request, true, $message);
        } catch (\Throwable $e) {
            Log::error('Reset password mail exception: ' . $e->getMessage());
            return $this->respond($request, false, 'Gagal mengirim email. Error: ' . $e->getMessage());
        }
    }

    private function resolveUser(string $credential): ?object
    {
        return DB::table('mst_user')
            ->where('email', $credential)
            ->orWhere('id', $credential)
            ->orWhere('identity_id', $credential)
            ->first();
    }

    private function respond(
        Request $request,
        bool $success,
        string $message,
        int $status = 422,
        ?int $retryAfter = null,
    ) {
        if ($request->ajax() || $request->expectsJson()) {
            $payload = ['success' => $success, 'message' => $message];
            if ($retryAfter !== null) {
                $payload['retry_after'] = $retryAfter;
            }

            return response()->json($payload, $success ? 200 : $status);
        }

        if ($success) {
            return back()->with('status', $message);
        }

        return back()->withErrors(['credential' => $message]);
    }
}