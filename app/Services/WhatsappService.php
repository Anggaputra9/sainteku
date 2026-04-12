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
            ])->withoutVerifying()
                ->post($this->baseUrl . '/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
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

        // Ambil judul, tanggal, dan kategori sesuai tipe
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
