<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\WhatsarClient;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappSettingController extends Controller
{
    public function __construct(
        protected WhatsarClient $whatsar,
        protected WhatsappService $whatsapp
    ) {}

    private function guardAdmin(): void
    {
        abort_unless(
            Auth::user()?->roles()->where('role_code', 'ADM')->exists(),
            403,
            'Hanya administrator yang boleh mengakses pengaturan WhatsApp.'
        );
    }

    public function index()
    {
        $this->guardAdmin();

        $health = $this->whatsar->health();
        $sessions = $this->whatsar->isConfigured()
            ? $this->whatsar->listSessions()
            : [];

        return view('settings.whatsapp.index', compact('health', 'sessions'))
            ->with('title', 'Pengaturan WhatsApp');
    }

    public function storeSession(Request $request)
    {
        $this->guardAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:64', 'regex:/^[a-zA-Z0-9_-]+$/'],
        ], [
            'name.regex' => 'Nama session hanya boleh huruf, angka, strip, dan underscore.',
        ]);

        $session = $this->whatsar->createSession($data['name']);
        if (!$session) {
            return back()->with('error', 'Gagal membuat session. Pastikan Whatsar berjalan dan API key valid.');
        }

        return redirect()
            ->route('settings.whatsapp.index', ['pair' => $session['id'] ?? null])
            ->with('success', 'Session berhasil dibuat. Scan QR untuk menghubungkan WhatsApp.');
    }

    public function destroySession(string $sessionId)
    {
        $this->guardAdmin();

        if (!$this->whatsar->deleteSession($sessionId)) {
            return back()->with('error', 'Gagal menghapus session.');
        }

        return back()->with('success', 'Session berhasil dihapus.');
    }

    public function reconnectSession(string $sessionId)
    {
        $this->guardAdmin();

        $session = $this->whatsar->reconnectSession($sessionId);
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai pairing ulang. Coba hapus session dan buat baru.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data'    => $session,
            'message' => 'Pairing ulang dimulai. Scan QR sebelum kedaluwarsa (~2 menit).',
        ]);
    }

    public function qr(string $sessionId)
    {
        $this->guardAdmin();

        $qr = $this->whatsar->getQr($sessionId);

        if (!$qr || empty($qr['image_base64'])) {
            $status = $this->whatsar->getStatus($sessionId);
            $state = $status['status'] ?? 'unknown';

            return response()->json([
                'success' => false,
                'message' => $state === 'failed'
                    ? 'QR kedaluwarsa. Klik "Scan Ulang" untuk generate QR baru.'
                    : 'QR belum tersedia. Tunggu sebentar atau mulai pairing ulang.',
                'data'    => ['status' => $state],
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $qr]);
    }

    public function status(string $sessionId)
    {
        $this->guardAdmin();

        $status = $this->whatsar->getStatus($sessionId);
        if (!$status) {
            return response()->json(['success' => false, 'message' => 'Session tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $status]);
    }

    public function listSessionsJson()
    {
        $this->guardAdmin();

        if (!$this->whatsar->isConfigured()) {
            return response()->json(['success' => true, 'data' => []]);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->whatsar->listSessions(),
        ]);
    }

    public function health()
    {
        $this->guardAdmin();

        $health = $this->whatsar->health();

        return response()->json([
            'success' => $health !== null,
            'data'    => $health,
        ], $health ? 200 : 503);
    }

    public function testSend(Request $request)
    {
        $this->guardAdmin();

        $data = $request->validate([
            'phone'   => ['required', 'string', 'min:8', 'max:20'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $message = $data['message'] ?? "Tes WhatsApp dari Sainteku — {$user->name}";

        $ok = $this->whatsapp->sendMessage($data['phone'], $message);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $ok,
                'message' => $ok ? 'Pesan uji berhasil dikirim.' : 'Gagal mengirim pesan. Cek session connected & log.',
            ], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $ok
            ? 'Pesan uji berhasil dikirim.'
            : 'Gagal mengirim pesan uji.');
    }
}