<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

/**
 * MailService
 *
 * Wrapper di atas EmailSenderService yang fokus pada:
 * - Pengiriman email aplikasi (notifikasi, verifikasi, reset password, dll)
 * - Otomatis pakai EmailSetting aktif (default) dari database
 * - Mendukung pengiriman pakai Blade view (HTML) maupun raw text
 * - Aman dipakai di mana saja: kalau setting belum ada / quota habis,
 *   akan dilog tanpa melempar exception ke flow utama.
 *
 * Cara pakai:
 *   MailService::send($to, $subject, 'emails.notification', ['user' => $user]);
 *   MailService::sendRaw($to, $subject, "Halo, ada update...");
 *   MailService::sendNotification($user, [...data NotifService...]);
 */
class MailService
{
    /**
     * Kirim email pakai Blade view sebagai body HTML.
     *
     * @param  string|array  $to       Email tujuan (boleh array)
     * @param  string        $subject  Subjek email
     * @param  string        $view     Nama view blade (cth: 'emails.notification')
     * @param  array         $data     Data yang dilempar ke view
     * @return array{success: bool, message: string}
     */
    public static function send($to, string $subject, string $view, array $data = []): array
    {
        $setting = EmailSetting::getActiveDefault();
        if (!$setting) {
            Log::warning("MailService::send dibatalkan, tidak ada EmailSetting default yang aktif.");
            return ['success' => false, 'message' => 'Tidak ada konfigurasi email aktif.'];
        }

        try {
            $html = View::make($view, $data)->render();
            return self::dispatchHtml($setting, $to, $subject, $html);
        } catch (\Throwable $e) {
            Log::error("MailService::send gagal merender view [{$view}]: " . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal merender template email.'];
        }
    }

    /**
     * Kirim email raw text. Untuk notifikasi cepat tanpa template.
     *
     * @return array{success: bool, message: string}
     */
    public static function sendRaw($to, string $subject, string $body): array
    {
        $setting = EmailSetting::getActiveDefault();
        if (!$setting) {
            Log::warning("MailService::sendRaw dibatalkan, tidak ada EmailSetting default.");
            return ['success' => false, 'message' => 'Tidak ada konfigurasi email aktif.'];
        }

        $recipients = is_array($to) ? $to : [$to];
        $lastResult = ['success' => false, 'message' => 'Tidak ada penerima.'];
        foreach ($recipients as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
            $lastResult = EmailSenderService::sendRawEmail($setting, $email, $subject, $body);
            if (!$lastResult['success']) {
                Log::warning("MailService::sendRaw ke {$email} gagal: " . $lastResult['message']);
            }
        }
        return $lastResult;
    }

    /**
     * Kirim email notifikasi sistem dengan layout standar.
     * Dipanggil otomatis dari NotifService ketika ada notif baru,
     * supaya user tidak hanya dapat notif in-app tapi juga email.
     *
     * Struktur $data sama dengan yang dipakai NotifService / GlobalNotification:
     *   [
     *     'action'       => 'mengajukan review untuk soal',
     *     'item_name'    => 'UTS (Algoritma)',
     *     'type'         => 'Tashih Soal',
     *     'url'          => route('...'),
     *     'sender_name'  => 'Budi Dosen',     // optional
     *   ]
     */
    public static function sendNotification($user, array $data): array
    {
        // Toleransi: $user boleh model User, boleh string email, boleh array
        $email = is_object($user) ? ($user->email ?? null) : $user;
        $name  = is_object($user) ? ($user->name ?? '') : '';

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email penerima tidak valid.'];
        }

        $subject = '[' . config('app.name') . '] Notifikasi: ' . ($data['type'] ?? 'Sistem');
        $payload = array_merge([
            'recipient_name' => $name,
            'app_name'       => config('app.name'),
            'app_url'        => config('app.url'),
            'sender_name'    => $data['sender_name'] ?? (auth()->user()->name ?? 'Sistem'),
        ], $data);

        return self::send($email, $subject, 'emails.notification', $payload);
    }

    /**
     * Kirim email password reset. Override dari ForgotPasswordController.
     */
    public static function sendPasswordReset(string $to, string $token): array
    {
        $resetLink = url('/reset-password/' . $token);
        return self::send($to, 'Reset Password - ' . config('app.name'), 'emails.reset-password', [
            'email'     => $to,
            'resetLink' => $resetLink,
        ]);
    }

    /**
     * Kirim email verifikasi alamat email user.
     */
    public static function sendEmailVerification(string $to, string $verifyLink, string $name = ''): array
    {
        return self::send($to, 'Verifikasi Email - ' . config('app.name'), 'emails.verify-email', [
            'email'      => $to,
            'name'       => $name,
            'verifyLink' => $verifyLink,
        ]);
    }

    /**
     * Internal helper untuk dispatch HTML body ke transport yang sesuai.
     * Untuk SMTP / native transport pakai Mail::send agar HTML utuh,
     * untuk HTTP API tetap pakai EmailSenderService::sendRawEmail
     * karena providernya sudah handle HTML di payload.
     */
    private static function dispatchHtml(EmailSetting $setting, $to, string $subject, string $html): array
    {
        $authMode = strtolower($setting->auth_mode ?: 'smtp');
        $provider = strtolower($setting->provider);
        $recipients = is_array($to) ? $to : [$to];

        // HTTP API providers: pakai sendRawEmail (sudah handle HTML di payload)
        if ($authMode === 'api' && in_array($provider, ['brevo', 'sendgrid', 'mailtrap', 'elasticemail'], true)) {
            $result = ['success' => false, 'message' => 'Tidak ada penerima.'];
            foreach ($recipients as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
                // Untuk HTTP API kita kirim HTML utuh sebagai "body"
                // EmailSenderService akan inject ke htmlContent (Brevo) atau text (lainnya)
                $result = EmailSenderService::sendRawEmail($setting, $email, $subject, $html);
                if (!$result['success']) {
                    Log::warning("MailService HTTP API ke {$email} gagal: " . $result['message']);
                }
            }
            return $result;
        }

        // SMTP atau native Laravel transport: pakai Mail::html()
        try {
            EmailSenderService::applyActiveSetting($setting);
            Mail::html($html, function ($message) use ($recipients, $subject) {
                $message->to($recipients)->subject($subject);
            });
            $setting->incrementSent(count($recipients));
            return ['success' => true, 'message' => 'Email berhasil dikirim.'];
        } catch (\Throwable $e) {
            Log::error("MailService dispatchHtml gagal: " . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal kirim email: ' . $e->getMessage()];
        }
    }
}
