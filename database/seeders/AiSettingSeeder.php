<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AiSetting;

class AiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh konfigurasi OpenAI (default)
        AiSetting::create([
            'name' => 'OpenAI GPT-4o Mini',
            'provider' => 'openai',
            'api_key' => '', // Kosongkan, admin isi manual
            'api_endpoint' => 'https://api.openai.com/v1',
            'model' => 'gpt-4o-mini',
            'temperature' => 0.7,
            'max_tokens' => 2000,
            'top_p' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'daily_limit' => 0, // unlimited
            'daily_used' => 0,
            'cost_per_1k_tokens' => 0.00015, // $0.15 per 1M tokens
            'total_cost' => 0,
            'is_active' => false, // Nonaktif sampai API key diisi
            'is_default' => true,
            'priority' => 1,
            'notes' => 'Konfigurasi default OpenAI. Isi API key untuk mengaktifkan.',
        ]);

        // Contoh konfigurasi Anthropic Claude
        AiSetting::create([
            'name' => 'Anthropic Claude Sonnet 4',
            'provider' => 'anthropic',
            'api_key' => '',
            'api_endpoint' => 'https://api.anthropic.com/v1',
            'model' => 'claude-sonnet-4',
            'temperature' => 0.7,
            'max_tokens' => 4096,
            'top_p' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'daily_limit' => 0,
            'daily_used' => 0,
            'cost_per_1k_tokens' => 0.003, // $3 per 1M tokens
            'total_cost' => 0,
            'is_active' => false,
            'is_default' => false,
            'priority' => 2,
            'notes' => 'Claude Sonnet 4 untuk tugas kompleks.',
        ]);

        // Contoh konfigurasi Google Gemini (Free tier)
        AiSetting::create([
            'name' => 'Google Gemini 1.5 Flash',
            'provider' => 'google',
            'api_key' => '',
            'api_endpoint' => 'https://generativelanguage.googleapis.com/v1',
            'model' => 'gemini-1.5-flash',
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'top_p' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'daily_limit' => 1500, // Free tier limit
            'daily_used' => 0,
            'cost_per_1k_tokens' => 0, // Free tier
            'total_cost' => 0,
            'is_active' => false,
            'is_default' => false,
            'priority' => 3,
            'notes' => 'Gemini Flash gratis dengan limit 1500 request/hari.',
        ]);

        // Contoh konfigurasi Groq (Free tier dengan rate limit tinggi)
        AiSetting::create([
            'name' => 'Groq Llama 3.3 70B',
            'provider' => 'groq',
            'api_key' => '',
            'api_endpoint' => 'https://api.groq.com/openai/v1',
            'model' => 'llama-3.3-70b-versatile',
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'top_p' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'daily_limit' => 0,
            'daily_used' => 0,
            'cost_per_1k_tokens' => 0, // Free tier
            'total_cost' => 0,
            'is_active' => false,
            'is_default' => false,
            'priority' => 4,
            'notes' => 'Groq gratis dengan inference sangat cepat.',
        ]);
    }
}
