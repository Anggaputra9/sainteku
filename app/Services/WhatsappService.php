<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $driver;
    protected bool $enabled;
    protected ?string $fonnteToken;
    protected string $fonnteBaseUrl;
    protected WhatsarClient $whatsar;

    public function __construct(?WhatsarClient $whatsar = null)
    {
        $this->driver = config('whatsapp.driver', 'whatsar');
        $this->enabled = (bool) config('whatsapp.enabled', true);
        $this->fonnteToken = config('whatsapp.fonnte.token');
        $this->fonnteBaseUrl = config('whatsapp.fonnte.base_url', 'https://api.fonnte.com');
        $this->whatsar = $whatsar ?? new WhatsarClient();
    }

    public function sendMessage($target, $message): bool
    {
        if (!$this->enabled) {
            Log::info('WhatsApp dinonaktifkan (WHATSAPP_ENABLED=false)');
            return false;
        }

        $target = $this->formatPhoneNumber($target);

        Log::info('Mengirim WhatsApp', [
            'driver'  => $this->driver,
            'target'  => $target,
            'message' => mb_substr($message, 0, 50) . '...',
        ]);

        return match ($this->driver) {
            'whatsar' => $this->sendViaWhatsar($target, $message),
            'fonnte'  => $this->sendViaFonnte($target, $message),
            'log'     => $this->sendViaLog($target, $message),
            default   => $this->sendViaWhatsar($target, $message),
        };
    }

    public function sendPasswordReset(string $phone, string $token, string $name = ''): bool
    {
        if (!$phone) {
            return false;
        }

        $resetLink = url('/reset-password/' . $token);
        $appName = config('app.name', 'Sainteku');
        $greeting = $name !== '' ? "Yth. *{$name}*" : 'Halo';

        $message = "🔐 *RESET PASSWORD*\n\n";
        $message .= "{$greeting}\n\n";
        $message .= "Kami menerima permintaan reset password untuk akun {$appName} Anda.\n\n";
        $message .= "Klik link berikut (berlaku *60 menit*):\n";
        $message .= "{$resetLink}\n\n";
        $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.\n\n";
        $message .= "_{$appName}_";

        return $this->sendMessage($phone, $message);
    }

    public function notifyApproved($user, $achievement, $type = 'mahasiswa'): bool
    {
        if (!$user->phone_number) {
            Log::warning('Nomor WA tidak ada', ['user' => $user->id]);
            return false;
        }

        $judul = ($type == 'dosen') ? $achievement->judul : $achievement->title;
        $kategori = ($type == 'dosen')
            ? ($achievement->kategori->nama ?? 'Prestasi Dosen')
            : ($achievement->type->description ?? 'Prestasi Mahasiswa');

        $tanggal = ($type == 'dosen')
            ? date('d/m/Y', strtotime($achievement->tanggal))
            : date('d/m/Y', strtotime($achievement->achievement_date));

        $message = "✅ *PENGUMUMAN PRESTASI*\n\n";
        $message .= "Yth. *{$user->name}*\n\n";
        $message .= "Selamat! Prestasi Anda telah *DISETUJUI* oleh admin.\n\n";
        $message .= "📋 *Detail Prestasi:*\n";
        $message .= "──────────────────\n";
        $message .= "🏆 *Judul:* {$judul}\n";
        $message .= "📅 *Tanggal:* {$tanggal}\n";
        $message .= "🏷️ *Kategori:* {$kategori}\n";
        $message .= "──────────────────\n\n";
        $message .= "💡 Prestasi Anda akan ditampilkan di portofolio.\n\n";
        $message .= "_Terima kasih atas dedikasi Anda._\n";
        $message .= "Sainteku";

        return $this->sendMessage($user->phone_number, $message);
    }

    public function notifyRejected($user, $achievement, $note, $type = 'mahasiswa'): bool
    {
        if (!$user->phone_number) {
            Log::warning('Nomor WA tidak ada', ['user' => $user->id]);
            return false;
        }

        $judul = ($type == 'dosen') ? $achievement->judul : $achievement->title;

        $message = "⚠️ *PENGUMUMAN PRESTASI*\n\n";
        $message .= "Yth. *{$user->name}*\n\n";
        $message .= "Mohon maaf, prestasi Anda *DITOLAK* oleh admin.\n\n";
        $message .= "📋 *Detail Prestasi:*\n";
        $message .= "──────────────────\n";
        $message .= "🏆 *Judul:* {$judul}\n";
        $message .= "📝 *Catatan Penolakan:*\n";
        $message .= "{$note}\n";
        $message .= "──────────────────\n\n";
        $message .= "📌 Silakan perbaiki sesuai catatan dan ajukan ulang.\n\n";
        $message .= "_Terima kasih._\n";
        $message .= "Sainteku";

        return $this->sendMessage($user->phone_number, $message);
    }

    public function pickSession(): ?string
    {
        $default = config('whatsapp.whatsar.default_session');
        if ($default) {
            $status = $this->whatsar->getStatus($default);
            if ($status && ($status['connected'] ?? false)) {
                return $default;
            }
        }

        $connected = $this->whatsar->connectedSessions();
        if (empty($connected)) {
            Log::warning('Whatsar: tidak ada session connected');
            return null;
        }

        $picked = $connected[array_rand($connected)];

        return $picked['id'] ?? null;
    }

    protected function sendViaWhatsar(string $target, string $message): bool
    {
        $sessionId = $this->pickSession();
        if (!$sessionId) {
            return false;
        }

        $result = $this->whatsar->sendText($sessionId, $target, $message, true);

        if ($result !== null) {
            Log::info('WhatsApp sukses (Whatsar)', [
                'target'     => $target,
                'session_id' => $sessionId,
                'response'   => $result,
            ]);
            return true;
        }

        return false;
    }

    protected function sendViaFonnte(string $target, string $message): bool
    {
        if (!$this->fonnteToken) {
            Log::error('FONNTE_TOKEN tidak ditemukan di .env');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->fonnteToken,
            ])->withoutVerifying()
                ->post($this->fonnteBaseUrl . '/send', [
                    'target'      => $target,
                    'message'     => $message,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp sukses (Fonnte)', [
                    'target'   => $target,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('WhatsApp gagal (Fonnte)', [
                'target' => $target,
                'error'  => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp exception (Fonnte)', ['message' => $e->getMessage()]);
        }

        return false;
    }

    protected function sendViaLog(string $target, string $message): bool
    {
        Log::info('WhatsApp (log driver)', ['target' => $target, 'message' => $message]);
        return true;
    }

    protected function formatPhoneNumber($number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        return $number;
    }
}