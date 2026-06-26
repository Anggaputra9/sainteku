<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AiSetting extends Model
{
    protected $table = 'ai_settings';

    protected $fillable = [
        'name',
        'provider',
        'api_key',
        'api_endpoint',
        'model',
        'temperature',
        'max_tokens',
        'top_p',
        'frequency_penalty',
        'presence_penalty',
        'daily_limit',
        'daily_used',
        'last_reset_date',
        'last_used_at',
        'cost_per_1k_tokens',
        'total_cost',
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
        'daily_used' => 'integer',
        'priority' => 'integer',
        'max_tokens' => 'integer',
        'frequency_penalty' => 'integer',
        'presence_penalty' => 'integer',
        'temperature' => 'decimal:2',
        'top_p' => 'decimal:2',
        'cost_per_1k_tokens' => 'decimal:6',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Daftar provider AI yang didukung beserta preset konfigurasi.
     */
    public static function providers(): array
    {
        return [
            'openai' => [
                'label' => 'OpenAI',
                'models' => [
                    'gpt-4o' => 'GPT-4o (Latest)',
                    'gpt-4o-mini' => 'GPT-4o Mini',
                    'gpt-4-turbo' => 'GPT-4 Turbo',
                    'gpt-4' => 'GPT-4',
                    'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                ],
                'default_model' => 'gpt-4o-mini',
                'endpoint' => 'https://api.openai.com/v1',
                'key_hint' => 'API Key dari https://platform.openai.com/api-keys',
                'docs' => 'https://platform.openai.com/docs',
            ],

            'anthropic' => [
                'label' => 'Anthropic (Claude)',
                'models' => [
                    'claude-opus-4' => 'Claude Opus 4',
                    'claude-sonnet-4' => 'Claude Sonnet 4',
                    'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
                    'claude-3-opus-20240229' => 'Claude 3 Opus',
                    'claude-3-sonnet-20240229' => 'Claude 3 Sonnet',
                    'claude-3-haiku-20240307' => 'Claude 3 Haiku',
                ],
                'default_model' => 'claude-sonnet-4',
                'endpoint' => 'https://api.anthropic.com/v1',
                'key_hint' => 'API Key dari https://console.anthropic.com/settings/keys',
                'docs' => 'https://docs.anthropic.com',
            ],

            'google' => [
                'label' => 'Google AI (Gemini)',
                'models' => [
                    'gemini-2.0-flash-exp' => 'Gemini 2.0 Flash (Experimental)',
                    'gemini-1.5-pro' => 'Gemini 1.5 Pro',
                    'gemini-1.5-flash' => 'Gemini 1.5 Flash',
                    'gemini-pro' => 'Gemini Pro',
                ],
                'default_model' => 'gemini-1.5-flash',
                'endpoint' => 'https://generativelanguage.googleapis.com/v1',
                'key_hint' => 'API Key dari https://makersuite.google.com/app/apikey',
                'docs' => 'https://ai.google.dev/docs',
            ],

            'groq' => [
                'label' => 'Groq',
                'models' => [
                    'llama-3.3-70b-versatile' => 'Llama 3.3 70B',
                    'llama-3.1-70b-versatile' => 'Llama 3.1 70B',
                    'mixtral-8x7b-32768' => 'Mixtral 8x7B',
                    'gemma2-9b-it' => 'Gemma 2 9B',
                ],
                'default_model' => 'llama-3.3-70b-versatile',
                'endpoint' => 'https://api.groq.com/openai/v1',
                'key_hint' => 'API Key dari https://console.groq.com/keys',
                'docs' => 'https://console.groq.com/docs',
                'note' => 'Free tier dengan rate limit tinggi',
            ],

            'ollama' => [
                'label' => 'Ollama (Local)',
                'models' => [
                    'llama3.2' => 'Llama 3.2',
                    'llama3.1' => 'Llama 3.1',
                    'mistral' => 'Mistral',
                    'codellama' => 'Code Llama',
                    'phi3' => 'Phi-3',
                ],
                'default_model' => 'llama3.2',
                'endpoint' => 'http://localhost:11434',
                'key_hint' => 'Tidak perlu API key (local)',
                'docs' => 'https://ollama.ai/docs',
                'note' => 'Jalankan model AI lokal di server',
            ],

            'custom' => [
                'label' => 'Custom API',
                'models' => [],
                'default_model' => '',
                'endpoint' => '',
                'key_hint' => 'API Key dari provider custom',
                'docs' => '',
                'note' => 'Untuk API endpoint custom atau self-hosted',
            ],
        ];
    }

    /**
     * Enkripsi API key sebelum simpan.
     */
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

        return $this->daily_used < $this->daily_limit;
    }

    /**
     * Tambah counter setelah AI request berhasil.
     * Pakai atomic SQL increment agar aman untuk multi queue worker.
     */
    public function incrementUsage(int $count = 1, float $cost = 0): void
    {
        $today = now()->toDateString();
        $now = now();

        static::where('id', $this->id)
            ->where(function ($query) use ($today) {
                $query->whereNull('last_reset_date')
                    ->orWhereDate('last_reset_date', '!=', $today);
            })
            ->update([
                'daily_used' => 0,
                'last_reset_date' => $today,
                'updated_at' => $now,
            ]);

        static::where('id', $this->id)->update([
            'daily_used' => DB::raw('daily_used + ' . (int) $count),
            'total_cost' => DB::raw('total_cost + ' . sprintf('%.6f', $cost)),
            'last_used_at' => $now,
            'updated_at' => $now,
        ]);

        $this->refresh();
    }

    /**
     * Konfig aktif default (yang dipakai untuk AI request aplikasi).
     */
    public static function getActiveDefault(): ?self
    {
        return static::where('is_active', true)
            ->where('is_default', true)
            ->orderBy('priority')
            ->first();
    }
}
