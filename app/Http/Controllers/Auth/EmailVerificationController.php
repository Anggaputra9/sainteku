<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * EmailVerificationController
 *
 * Mengelola verifikasi alamat email user. Workflow:
 *  1. User (yang sudah login) klik tombol "Kirim Verifikasi" di profile
 *     -> POST /email/verification-notification
 *  2. Sistem generate token, simpan di tabel app_email_verifications,
 *     dan kirim email verifikasi pakai EmailSetting aktif via MailService.
 *  3. User klik tombol di email -> GET /email/verify/{token}
 *  4. Token divalidasi (60 menit), kolom email_verified_at di-set ke now().
 *
 * Tabel app_email_verifications dibuat dinamis di runtime kalau belum ada
 * supaya tidak butuh migration tambahan.
 */
class EmailVerificationController extends Controller
{
    private const TABLE = 'app_email_verifications';
    private const TOKEN_TTL_MINUTES = 60;

    /**
     * Halaman pengingat verifikasi (kalau dibutuhkan).
     */
    public function notice(Request $request)
    {
        $user = $request->user();
        if ($user && $user->email_verified_at) {
            return redirect()->route('profile.edit')->with('status', 'email-already-verified');
        }
        return view('auth.verify-email');
    }

    /**
     * Kirim ulang link verifikasi ke email user yang sedang login.
     */
    public function send(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return back()->with('status', 'email-already-verified');
        }

        $this->ensureTableExists();

        // Hapus token lama untuk email ini
        DB::table(self::TABLE)->where('email', $user->email)->delete();

        $token = Str::random(64);
        DB::table(self::TABLE)->insert([
            'email'      => $user->email,
            'user_id'    => $user->id,
            'token'      => hash('sha256', $token),
            'created_at' => Carbon::now(),
        ]);

        $verifyLink = url(route('verification.verify', ['token' => $token], false));
        $result = MailService::sendEmailVerification($user->email, $verifyLink, $user->name ?? '');

        if (!$result['success']) {
            return back()->with('error', 'Gagal mengirim email verifikasi: ' . $result['message']);
        }

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Endpoint yang diklik dari email user untuk konfirmasi verifikasi.
     * Tidak memerlukan auth: tapi token wajib valid.
     */
    public function verify(Request $request, string $token)
    {
        $this->ensureTableExists();

        $hashed = hash('sha256', $token);
        $record = DB::table(self::TABLE)->where('token', $hashed)->first();

        if (!$record) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid atau sudah dipakai.');
        }

        // Cek kadaluarsa
        if (Carbon::parse($record->created_at)->addMinutes(self::TOKEN_TTL_MINUTES)->isPast()) {
            DB::table(self::TABLE)->where('email', $record->email)->delete();
            return redirect()->route('login')->with('error', 'Tautan verifikasi sudah kadaluarsa. Silakan minta ulang.');
        }

        // Tandai user sebagai terverifikasi
        DB::table('mst_user')
            ->where('email', $record->email)
            ->update(['email_verified_at' => Carbon::now()]);

        // Bersihkan token
        DB::table(self::TABLE)->where('email', $record->email)->delete();

        // Kalau user lagi login, redirect ke profil. Kalau enggak, ke login.
        if (Auth::check()) {
            return redirect()->route('profile.edit')->with('status', 'email-verified');
        }
        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan login.');
    }

    /**
     * Bikin tabel verifikasi kalau belum ada. Idempotent.
     */
    private function ensureTableExists(): void
    {
        if (Schema::hasTable(self::TABLE)) return;

        Schema::create(self::TABLE, function ($table) {
            $table->id();
            $table->string('email')->index();
            $table->string('user_id')->nullable()->index();
            $table->string('token', 128);
            $table->timestamp('created_at')->nullable();
        });
    }
}
