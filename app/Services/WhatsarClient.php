<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsarClient
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('whatsapp.whatsar.url', 'http://127.0.0.1:8080'), '/');
        $this->apiKey = config('whatsapp.whatsar.api_key');
        $this->timeout = (int) config('whatsapp.whatsar.timeout', 30);
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function health(): ?array
    {
        return $this->request('GET', '/health', null, false);
    }

    public function listSessions(): array
    {
        return $this->request('GET', '/api/v1/sessions') ?? [];
    }

    public function createSession(string $name): ?array
    {
        return $this->request('POST', '/api/v1/sessions', ['name' => $name]);
    }

    public function getSession(string $id): ?array
    {
        return $this->request('GET', "/api/v1/sessions/{$id}");
    }

    public function getStatus(string $id): ?array
    {
        return $this->request('GET', "/api/v1/sessions/{$id}/status");
    }

    public function getQr(string $id): ?array
    {
        return $this->request('GET', "/api/v1/sessions/{$id}/qr");
    }

    public function deleteSession(string $id): bool
    {
        return $this->request('DELETE', "/api/v1/sessions/{$id}") !== null;
    }

    /**
     * Pairing ulang: hapus session gagal/expired lalu buat baru dengan nama sama.
     * Whatsar v0.1.0 belum punya endpoint reconnect — ini workaround aman.
     */
    public function reconnectSession(string $id): ?array
    {
        $session = $this->getSession($id);
        if (!$session) {
            return null;
        }

        $name = $session['name'] ?? 'session';
        $this->deleteSession($id);

        return $this->createSession($name);
    }

    public function needsReconnect(?array $status): bool
    {
        if (!$status) {
            return true;
        }

        if ($status['connected'] ?? false) {
            return false;
        }

        return in_array($status['status'] ?? '', ['failed', 'stopped'], true);
    }

    public function sendText(string $sessionId, string $to, string $text, bool $retry = false): ?array
    {
        return $this->request('POST', '/api/v1/messages/send', [
            'session_id' => $sessionId,
            'to'         => $to,
            'text'       => $text,
            'retry'      => $retry,
        ]);
    }

    public function connectedSessions(): array
    {
        return array_values(array_filter(
            $this->listSessions(),
            fn ($s) => ($s['connected'] ?? false) === true || ($s['status'] ?? '') === 'connected'
        ));
    }

    protected function request(string $method, string $path, ?array $body = null, bool $auth = true): ?array
    {
        if ($auth && !$this->isConfigured()) {
            Log::error('WhatsarClient: WHATSAR_API_KEY belum diset');
            return null;
        }

        try {
            $pending = Http::timeout($this->timeout)
                ->acceptJson()
                ->baseUrl($this->baseUrl);

            if ($auth) {
                $pending = $pending->withHeaders(['X-API-Key' => $this->apiKey]);
            }

            $response = match (strtoupper($method)) {
                'GET'    => $pending->get($path),
                'POST'   => $pending->post($path, $body ?? []),
                'DELETE' => $pending->delete($path),
                default  => throw new \InvalidArgumentException("Method {$method} tidak didukung"),
            };
        } catch (ConnectionException $e) {
            Log::error('WhatsarClient: koneksi gagal', [
                'path'  => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }

        $json = $response->json();

        if (!$response->successful() || !($json['success'] ?? false)) {
            Log::error('WhatsarClient: request gagal', [
                'method' => $method,
                'path'   => $path,
                'status' => $response->status(),
                'error'  => $json['error'] ?? $response->body(),
            ]);
            return null;
        }

        return $json['data'] ?? [];
    }
}