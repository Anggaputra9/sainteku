<?php

namespace App\Services;

use App\Models\EmailSetting;
use Composer\CaBundle\CaBundle;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailSenderService
{
    /**
     * Apply konfigurasi email dari database ke runtime config Laravel.
     * Hanya dipakai untuk mode SMTP atau API yang punya transport native Laravel
     * (Resend / Mailgun / Postmark).
     *
     * Untuk mode API provider yang tidak punya transport native Laravel
     * (Brevo, SendGrid, Mailtrap, Elastic Email), pengiriman dilakukan via
     * HTTP API langsung — JANGAN gunakan applyActiveSetting() untuk itu.
     */
    public static function applyActiveSetting(?EmailSetting $setting = null): ?EmailSetting
    {
        $setting = $setting ?? EmailSetting::getActiveDefault();
        if (!$setting) {
            return null;
        }

        if (!$setting->hasQuota()) {
            Log::warning("Email setting [{$setting->name}] sudah mencapai batas harian.");
            $fallback = EmailSetting::where('is_active', true)
                ->where('id', '!=', $setting->id)
                ->orderBy('priority')
                ->get()
                ->first(fn ($s) => $s->hasQuota());

            if (!$fallback) return null;
            $setting = $fallback;
        }

        $authMode = strtolower($setting->auth_mode ?: 'smtp');

        Config::set('mail.from.address', $setting->from_email ?: config('mail.from.address'));
        Config::set('mail.from.name', $setting->from_name ?: config('mail.from.name'));

        if ($authMode === 'smtp') {
            self::applySmtp($setting);
            return $setting;
        }

        // API mode dengan transport native Laravel
        $provider = strtolower($setting->provider);
        switch ($provider) {
            case 'resend':
                Config::set('mail.default', 'resend');
                Config::set('services.resend.key', $setting->api_key);
                return $setting;

            case 'mailgun':
                Config::set('mail.default', 'mailgun');
                Config::set('services.mailgun.domain', $setting->api_domain);
                Config::set('services.mailgun.secret', $setting->api_key);
                Config::set('services.mailgun.endpoint', 'api.mailgun.net');
                return $setting;

            case 'postmark':
                Config::set('mail.default', 'postmark');
                Config::set('services.postmark.token', $setting->api_key);
                return $setting;

            default:
                // Provider HTTP-only yang bukan native Laravel transport.
                // Pengirim harus pakai sendRawEmail() agar di-route ke HTTP API.
                return $setting;
        }
    }

    private static function applySmtp(EmailSetting $setting): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => $setting->host,
            'port'       => $setting->port ?? 587,
            'username'   => $setting->username,
            'password'   => $setting->password,
            'encryption' => $setting->encryption ?: 'tls',
            'timeout'    => null,
            'auth_mode'  => null,
        ]);
    }

    /**
     * Daftar provider yang harus dikirim via HTTP API langsung
     * (tidak ada transport native Laravel).
     */
    private static function isHttpApiProvider(string $provider): bool
    {
        return in_array(strtolower($provider), ['brevo', 'sendgrid', 'mailtrap', 'elasticemail'], true);
    }

    /**
     * HTTP client dengan CA bundle yang benar.
     *
     * Di Windows / Laragon, PHP sering tidak punya curl.cainfo yang valid.
     * Akibatnya request HTTPS ke api.brevo.com / api.sendgrid.com gagal dengan
     * "cURL error 60: SSL peer certificate ... was not OK".
     *
     * Solusinya: pakai bundle CA dari composer/ca-bundle (sudah ikut Laravel)
     * yang selalu up-to-date dan portable lintas OS.
     *
     * Di environment local kalau bundle tidak terbaca juga, fallback ke
     * verify=false dengan warning di log — supaya developer tetap bisa coba.
     */
    private static function httpClient(): PendingRequest
    {
        $client = Http::acceptJson();

        if (class_exists(CaBundle::class)) {
            $caPath = CaBundle::getSystemCaRootBundlePath();
            if ($caPath && @is_readable($caPath)) {
                return $client->withOptions(['verify' => $caPath]);
            }
        }

        // Last resort untuk dev environment
        if (app()->environment('local')) {
            Log::warning('CA bundle tidak ditemukan, SSL verify dimatikan untuk environment local. JANGAN dipakai di production.');
            return $client->withOptions(['verify' => false]);
        }

        // Production tanpa CA bundle: biarkan default cURL handler menghandle
        // (akan throw error yang jelas, lebih aman daripada disable verify).
        return $client;
    }


    /**
     * Pengirim universal yang otomatis route ke SMTP / native transport / HTTP API
     * berdasarkan setting.auth_mode dan provider.
     *
     * @return array{success: bool, message: string}
     */
    public static function sendRawEmail(EmailSetting $setting, string $to, string $subject, string $body): array
    {
        $authMode = strtolower($setting->auth_mode ?: 'smtp');
        $provider = strtolower($setting->provider);

        try {
            // Mode API + provider HTTP-only → kirim via HTTP API
            if ($authMode === 'api' && self::isHttpApiProvider($provider)) {
                return self::sendViaHttpApi($setting, $to, $subject, $body);
            }

            // Selain itu: pakai mailer Laravel (SMTP atau native transport)
            self::applyActiveSetting($setting);

            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            $setting->incrementSent(1);
            return ['success' => true, 'message' => "Email berhasil dikirim ke {$to}"];
        } catch (\Throwable $e) {
            Log::error("sendRawEmail gagal [{$setting->name}]: " . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal: ' . $e->getMessage()];
        }
    }

    /**
     * Kirim email via HTTP API resmi vendor.
     */
    private static function sendViaHttpApi(EmailSetting $setting, string $to, string $subject, string $body): array
    {
        $provider = strtolower($setting->provider);
        $fromEmail = $setting->from_email ?: config('mail.from.address');
        $fromName  = $setting->from_name ?: config('mail.from.name');

        switch ($provider) {
            case 'brevo':
                // Endpoint resmi Brevo: POST https://api.brevo.com/v3/smtp/email
                // Header: api-key: xkeysib-...
                $resp = self::httpClient()
                    ->withHeaders([
                        'api-key'      => $setting->api_key,
                        'content-type' => 'application/json',
                    ])->post('https://api.brevo.com/v3/smtp/email', [
                        'sender'      => ['name' => $fromName, 'email' => $fromEmail],
                        'to'          => [['email' => $to]],
                        'subject'     => $subject,
                        'textContent' => $body,
                        'htmlContent' => '<pre style="font-family:inherit;white-space:pre-wrap;">' . e($body) . '</pre>',
                    ]);
                break;

            case 'sendgrid':
                // POST https://api.sendgrid.com/v3/mail/send, Authorization: Bearer SG.xxx
                $resp = self::httpClient()
                    ->withToken($setting->api_key)
                    ->post('https://api.sendgrid.com/v3/mail/send', [
                        'personalizations' => [['to' => [['email' => $to]]]],
                        'from'    => ['email' => $fromEmail, 'name' => $fromName],
                        'subject' => $subject,
                        'content' => [['type' => 'text/plain', 'value' => $body]],
                    ]);
                break;

            case 'mailtrap':
                // POST https://send.api.mailtrap.io/api/send, Authorization: Bearer <token>
                $resp = self::httpClient()
                    ->withToken($setting->api_key)
                    ->post('https://send.api.mailtrap.io/api/send', [
                        'from'    => ['email' => $fromEmail, 'name' => $fromName],
                        'to'      => [['email' => $to]],
                        'subject' => $subject,
                        'text'    => $body,
                    ]);
                break;

            case 'elasticemail':
                // POST https://api.elasticemail.com/v4/emails, Header X-ElasticEmail-ApiKey
                $resp = self::httpClient()
                    ->withHeaders([
                        'X-ElasticEmail-ApiKey' => $setting->api_key,
                        'content-type'          => 'application/json',
                    ])->post('https://api.elasticemail.com/v4/emails', [
                        'Recipients' => [['Email' => $to]],
                        'Content'    => [
                            'From'    => "{$fromName} <{$fromEmail}>",
                            'Subject' => $subject,
                            'Body'    => [[
                                'ContentType' => 'PlainText',
                                'Content'     => $body,
                            ]],
                        ],
                    ]);
                break;

            default:
                return ['success' => false, 'message' => "Provider [{$provider}] belum mendukung mode HTTP API."];
        }

        if (!$resp->successful()) {
            $reason = $resp->json('message')
                ?? $resp->json('errors.0.message')
                ?? $resp->body();
            Log::warning("HTTP API {$provider} gagal: HTTP {$resp->status()} — {$reason}");
            return [
                'success' => false,
                'message' => "API {$provider} balas HTTP {$resp->status()}: {$reason}",
            ];
        }

        $setting->incrementSent(1);
        return ['success' => true, 'message' => "Email berhasil dikirim ke {$to} via {$provider} API"];
    }

    /**
     * Kirim test email cepat untuk verifikasi konfigurasi.
     */
    public static function sendTest(EmailSetting $setting, string $to): array
    {
        $body = "Tes pengiriman email dari konfigurasi [{$setting->name}] ({$setting->provider} - {$setting->auth_mode}).\n\n"
              . "Jika kamu menerima email ini, konfigurasi sudah berjalan dengan benar.";
        $subject = '[TEST] ' . $setting->name . ' - ' . config('app.name');

        return self::sendRawEmail($setting, $to, $subject, $body);
    }
}
