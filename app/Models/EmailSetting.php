<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmailSetting extends Model
{
    protected $table = 'app_email_settings';

    protected $fillable = [
        'name',
        'provider',
        'auth_mode',
        'from_email',
        'from_name',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'api_key',
        'api_domain',
        'api_secret',
        'daily_limit',
        'daily_sent',
        'last_reset_date',
        'last_used_at',
        'is_active',
        'is_default',
        'priority',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'last_reset_date' => 'date',
        'last_used_at' => 'datetime',
        'daily_limit' => 'integer',
        'daily_sent' => 'integer',
        'priority' => 'integer',
        'port' => 'integer',
    ];

    /**
     * Daftar provider yang didukung beserta preset host/port/encryption
     * dari dokumentasi resmi tiap provider.
     *
     * Struktur:
     * - label, free_limit, note: untuk tampilan
     * - auth_modes: ['smtp', 'api'] - mode auth yang didukung
     * - default_mode: mode default saat provider dipilih
     * - smtp: preset SMTP (host/port/encryption + hints)
     * - api: hints untuk konfigurasi API
     */
    public static function providers(): array
    {
        return [
            'smtp' => [
                'label'        => 'SMTP / PHPMailer',
                'free_limit'   => null,
                'note'         => 'Server SMTP custom (Gmail, cPanel, dsb)',
                'auth_modes'   => ['smtp'],
                'default_mode' => 'smtp',
                'smtp' => [
                    'host'        => '',
                    'port'        => 587,
                    'encryption'  => 'tls',
                    'username_hint' => 'Email / username SMTP',
                    'password_hint' => 'Password / app password SMTP',
                ],
                'api' => null,
            ],

            'brevo' => [
                'label'        => 'Brevo (Sendinblue)',
                'free_limit'   => 300,
                'note'         => '300 email/hari gratis',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'smtp',
                'smtp' => [
                    'host'          => 'smtp-relay.brevo.com',
                    'port'          => 587,
                    'encryption'    => 'tls',
                    'username_hint' => 'Email login akun Brevo',
                    'password_hint' => 'SMTP Key dari dashboard Brevo (bukan password login)',
                ],
                'api' => [
                    'endpoint'  => 'https://api.brevo.com/v3',
                    'key_hint'  => 'API Key v3 (xkeysib-...) dari dashboard Brevo > SMTP & API',
                ],
            ],

            'sendgrid' => [
                'label'        => 'SendGrid',
                'free_limit'   => 100,
                'note'         => '100 email/hari gratis selamanya',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'smtp',
                'smtp' => [
                    'host'          => 'smtp.sendgrid.net',
                    'port'          => 587,
                    'encryption'    => 'tls',
                    'username_hint' => 'Username harus persis: apikey',
                    'password_hint' => 'API Key SendGrid (SG.xxxxxxxx)',
                    'username_fixed' => 'apikey',
                ],
                'api' => [
                    'endpoint' => 'https://api.sendgrid.com/v3',
                    'key_hint' => 'API Key (SG.xxxxxxxx) dari Settings > API Keys',
                ],
            ],

            'resend' => [
                'label'        => 'Resend',
                'free_limit'   => 100,
                'note'         => '100 email/hari, 3000/bulan gratis',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'api',
                'smtp' => [
                    'host'          => 'smtp.resend.com',
                    'port'          => 465,
                    'encryption'    => 'ssl',
                    'username_hint' => 'Username harus persis: resend',
                    'password_hint' => 'API Key (re_xxxxxxxx)',
                    'username_fixed' => 'resend',
                ],
                'api' => [
                    'endpoint' => 'https://api.resend.com',
                    'key_hint' => 'API Key (re_xxxxxxxx) dari dashboard Resend',
                ],
            ],

            'mailgun' => [
                'label'        => 'Mailgun',
                'free_limit'   => 100,
                'note'         => '100 email/hari pada flex plan',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'api',
                'smtp' => [
                    'host'          => 'smtp.mailgun.org',
                    'port'          => 587,
                    'encryption'    => 'tls',
                    'username_hint' => 'postmaster@<your-domain>',
                    'password_hint' => 'SMTP password dari Mailgun domain settings',
                ],
                'api' => [
                    'endpoint'    => 'https://api.mailgun.net/v3',
                    'key_hint'    => 'Private API Key (key-xxxx)',
                    'need_domain' => true,
                ],
            ],

            'mailtrap' => [
                'label'        => 'Mailtrap',
                'free_limit'   => 200,
                'note'         => '200 email/hari free tier (sending)',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'smtp',
                'smtp' => [
                    'host'          => 'live.smtp.mailtrap.io',
                    'port'          => 587,
                    'encryption'    => 'tls',
                    'username_hint' => 'Username harus persis: api',
                    'password_hint' => 'API Token Mailtrap',
                    'username_fixed' => 'api',
                ],
                'api' => [
                    'endpoint' => 'https://send.api.mailtrap.io',
                    'key_hint' => 'API Token dari dashboard Mailtrap',
                ],
            ],

            'postmark' => [
                'label'        => 'Postmark',
                'free_limit'   => null,
                'note'         => 'Trial 100 email, berbayar setelahnya',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'api',
                'smtp' => [
                    'host'          => 'smtp.postmarkapp.com',
                    'port'          => 587,
                    'encryption'    => 'tls',
                    'username_hint' => 'Server API Token (sebagai username)',
                    'password_hint' => 'Server API Token (sama dengan username)',
                ],
                'api' => [
                    'endpoint' => 'https://api.postmarkapp.com',
                    'key_hint' => 'Server API Token dari Servers > API Tokens',
                ],
            ],

            'elasticemail' => [
                'label'        => 'Elastic Email',
                'free_limit'   => 100,
                'note'         => '100 email/hari gratis',
                'auth_modes'   => ['smtp', 'api'],
                'default_mode' => 'smtp',
                'smtp' => [
                    'host'          => 'smtp.elasticemail.com',
                    'port'          => 2525,
                    'encryption'    => 'tls',
                    'username_hint' => 'Email akun Elastic Email',
                    'password_hint' => 'API Key Elastic Email',
                ],
                'api' => [
                    'endpoint' => 'https://api.elasticemail.com/v4',
                    'key_hint' => 'API Key dari Settings > API',
                ],
            ],
        ];
    }

    /**
     * Mutator: enkripsi password sebelum simpan.
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = $value === null || $value === ''
            ? null
            : Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function setApiKeyAttribute($value): void
    {
        $this->attributes['api_key'] = $value === null || $value === ''
            ? null
            : Crypt::encryptString($value);
    }

    public function getApiKeyAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function setApiSecretAttribute($value): void
    {
        $this->attributes['api_secret'] = $value === null || $value === ''
            ? null
            : Crypt::encryptString($value);
    }

    public function getApiSecretAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Tampilan API key dengan mask (untuk listing).
     */
    public function getMaskedApiKeyAttribute(): ?string
    {
        $key = $this->api_key;
        if (!$key) return null;
        $len = strlen($key);
        if ($len <= 8) return str_repeat('•', $len);
        return substr($key, 0, 4) . str_repeat('•', max(0, $len - 8)) . substr($key, -4);
    }

    /**
     * Cek apakah quota harian masih ada.
     */
    public function hasQuota(): bool
    {
        if ($this->daily_limit <= 0) return true;

        // Reset counter kalau beda hari
        if ($this->last_reset_date?->toDateString() !== now()->toDateString()) {
            return true;
        }

        return $this->daily_sent < $this->daily_limit;
    }

    /**
     * Tambah counter setelah email berhasil dikirim.
     */
    public function incrementSent(int $count = 1): void
    {
        $today = now()->toDateString();
        if ($this->last_reset_date?->toDateString() !== $today) {
            $this->daily_sent = 0;
            $this->last_reset_date = $today;
        }
        $this->daily_sent += $count;
        $this->last_used_at = now();
        $this->save();
    }

    /**
     * Konfig aktif default (yang dipakai untuk pengiriman email aplikasi).
     */
    public static function getActiveDefault(): ?self
    {
        return static::where('is_active', true)
            ->where('is_default', true)
            ->orderBy('priority')
            ->first();
    }
}
