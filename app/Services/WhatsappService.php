<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->apiKey = env('FONNTE_TOKEN');

        if (!$this->apiKey) {
            Log::error('FONNTE_TOKEN tidak ditemukan di .env');
        }
    }

    /**
     * Kirim pesan WhatsApp biasa
     */
    public function sendMessage($target, $message)
    {
        try {
            $target = $this->formatPhoneNumber($target);

            Log::info('Mengirim WhatsApp', [
                'target' => $target,
                'message' => substr($message, 0, 50) . '...'
            ]);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->withoutVerifying() // Untuk local development
                ->post($this->baseUrl . '/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62', // Kode negara Indonesia
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp sukses', [
                    'target' => $target,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('WhatsApp gagal', [
                    'target' => $target,
                    'error' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Kirim notifikasi prestasi disetujui
     */
    public function notifyApproved($user, $achievement, $type = 'mahasiswa')
    {
        if (!$user->phone_number) {
            Log::warning('Nomor WA tidak ada', ['user' => $user->id]);
            return false;
        }

        // Ambil judul dan tanggal sesuai tipe
        $judul = ($type == 'dosen') ? $achievement->judul : $achievement->title;

        $tanggal = ($type == 'dosen')
            ? date('d/m/Y', strtotime($achievement->tanggal))
            : date('d/m/Y', strtotime($achievement->achievement_date));

        $message = "🎉 *PRESTASI DISETUJUI*\n\n";
        $message .= "Halo *{$user->name}*,\n";
        $message .= "Prestasi Anda telah disetujui oleh admin.\n\n";
        $message .= "📌 *Judul:* {$judul}\n";
        $message .= "📅 *Tanggal:* {$tanggal}\n\n";
        $message .= "Terima kasih telah berkontribusi.\n";
        $message .= "- SaintekU";

        return $this->sendMessage($user->phone_number, $message);
    }

    /**
     * Kirim notifikasi prestasi ditolak
     */
    public function notifyRejected($user, $achievement, $note, $type = 'mahasiswa')
    {
        if (!$user->phone_number) {
            Log::warning('Nomor WA tidak ada', ['user' => $user->id]);
            return false;
        }

        $judul = ($type == 'dosen') ? $achievement->judul : $achievement->title;

        $message = "⚠️ *PRESTASI DITOLAK*\n\n";
        $message .= "Halo *{$user->name}*,\n";
        $message .= "Mohon maaf, prestasi Anda ditolak.\n\n";
        $message .= "📌 *Judul:* {$judul}\n";
        $message .= "📝 *Catatan:* {$note}\n\n";
        $message .= "Silakan perbaiki dan ajukan ulang.\n";
        $message .= "- SaintekU";

        return $this->sendMessage($user->phone_number, $message);
    }

    /**
     * Format nomor telepon ke 62
     */
    private function formatPhoneNumber($number)
    {
        // Hapus semua karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali 0, ganti dengan 62
        if (substr($number, 0, 1) == '0') {
            $number = '62' . substr($number, 1);
        }
        // Jika tidak diawali 62, tambahkan 62
        elseif (substr($number, 0, 2) != '62') {
            $number = '62' . $number;
        }

        return $number;
    }
}
