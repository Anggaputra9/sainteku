<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * Send prompt to AI provider and get response.
     *
     * @param string $prompt
     * @param AiSetting|null $setting
     * @param array $options
     * @return array ['success' => bool, 'response' => string, 'error' => string|null, 'tokens' => int, 'cost' => float]
     */
    public function sendPrompt(string $prompt, ?AiSetting $setting = null, array $options = []): array
    {
        // Get default setting if not provided
        if (!$setting) {
            $setting = AiSetting::getActiveDefault();
            if (!$setting) {
                return [
                    'success' => false,
                    'response' => '',
                    'error' => 'No active AI configuration found.',
                    'tokens' => 0,
                    'cost' => 0,
                ];
            }
        }

        // Check if setting is active
        if (!$setting->is_active) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'AI configuration is not active.',
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        // Check quota
        if (!$setting->hasQuota()) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'Daily quota exceeded.',
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        try {
            // Route to appropriate provider
            $result = match ($setting->provider) {
                'openai' => $this->sendToOpenAI($prompt, $setting, $options),
                'anthropic' => $this->sendToAnthropic($prompt, $setting, $options),
                'google' => $this->sendToGoogle($prompt, $setting, $options),
                'groq' => $this->sendToGroq($prompt, $setting, $options),
                'ollama' => $this->sendToOllama($prompt, $setting, $options),
                'custom' => $this->sendToCustom($prompt, $setting, $options),
                default => [
                    'success' => false,
                    'response' => '',
                    'error' => 'Unsupported provider: ' . $setting->provider,
                    'tokens' => 0,
                    'cost' => 0,
                ],
            };

            // Track usage if successful
            if ($result['success']) {
                $setting->incrementUsage(1, $result['cost']);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('AI Service Error', [
                'provider' => $setting->provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'response' => '',
                'error' => $e->getMessage(),
                'tokens' => 0,
                'cost' => 0,
            ];
        }
    }

    /**
     * Send prompt to OpenAI API.
     */
    private function sendToOpenAI(string $prompt, AiSetting $setting, array $options): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $setting->api_key,
            'Content-Type' => 'application/json',
        ])->withoutVerifying()->timeout(60)->post($setting->api_endpoint . '/chat/completions', [
            'model' => $setting->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => $options['temperature'] ?? $setting->temperature,
            'max_tokens' => $options['max_tokens'] ?? $setting->max_tokens,
            'top_p' => $options['top_p'] ?? $setting->top_p,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'OpenAI API error: ' . $response->body(),
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        $tokens = $data['usage']['total_tokens'] ?? 0;
        $cost = ($tokens / 1000) * $setting->cost_per_1k_tokens;

        return [
            'success' => true,
            'response' => $content,
            'error' => null,
            'tokens' => $tokens,
            'cost' => $cost,
        ];
    }

    /**
     * Send prompt to Anthropic API (Claude).
     */
    private function sendToAnthropic(string $prompt, AiSetting $setting, array $options): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $setting->api_key,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->withoutVerifying()->timeout(60)->post($setting->api_endpoint . '/messages', [
            'model' => $setting->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => $options['max_tokens'] ?? $setting->max_tokens,
            'temperature' => $options['temperature'] ?? $setting->temperature,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'Anthropic API error: ' . $response->body(),
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        $data = $response->json();
        $content = $data['content'][0]['text'] ?? '';
        $tokens = ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0);
        $cost = ($tokens / 1000) * $setting->cost_per_1k_tokens;

        return [
            'success' => true,
            'response' => $content,
            'error' => null,
            'tokens' => $tokens,
            'cost' => $cost,
        ];
    }

    /**
     * Send prompt to Google AI API (Gemini).
     */
    private function sendToGoogle(string $prompt, AiSetting $setting, array $options): array
    {
        $response = Http::withoutVerifying()->timeout(60)->post(
            $setting->api_endpoint . '/models/' . $setting->model . ':generateContent?key=' . $setting->api_key,
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? $setting->temperature,
                    'maxOutputTokens' => $options['max_tokens'] ?? $setting->max_tokens,
                    'topP' => $options['top_p'] ?? $setting->top_p,
                ],
            ]
        );

        if (!$response->successful()) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'Google AI API error: ' . $response->body(),
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        $data = $response->json();
        $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $tokens = ($data['usageMetadata']['promptTokenCount'] ?? 0) + ($data['usageMetadata']['candidatesTokenCount'] ?? 0);
        $cost = ($tokens / 1000) * $setting->cost_per_1k_tokens;

        return [
            'success' => true,
            'response' => $content,
            'error' => null,
            'tokens' => $tokens,
            'cost' => $cost,
        ];
    }

    /**
     * Send prompt to Groq API.
     */
    private function sendToGroq(string $prompt, AiSetting $setting, array $options): array
    {
        // Groq uses OpenAI-compatible API
        return $this->sendToOpenAI($prompt, $setting, $options);
    }

    /**
     * Send prompt to Ollama (local).
     */
    private function sendToOllama(string $prompt, AiSetting $setting, array $options): array
    {
        $response = Http::withoutVerifying()->timeout(120)->post($setting->api_endpoint . '/api/generate', [
            'model' => $setting->model,
            'prompt' => $prompt,
            'stream' => false,
            'options' => [
                'temperature' => $options['temperature'] ?? $setting->temperature,
                'num_predict' => $options['max_tokens'] ?? $setting->max_tokens,
            ],
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'response' => '',
                'error' => 'Ollama API error: ' . $response->body(),
                'tokens' => 0,
                'cost' => 0,
            ];
        }

        $data = $response->json();
        $content = $data['response'] ?? '';

        return [
            'success' => true,
            'response' => $content,
            'error' => null,
            'tokens' => 0, // Ollama doesn't return token count
            'cost' => 0, // Local, no cost
        ];
    }

    /**
     * Send prompt to Custom API (OpenAI-compatible).
     * Most custom providers use OpenAI-compatible format.
     */
    private function sendToCustom(string $prompt, AiSetting $setting, array $options): array
    {
        // Try OpenAI-compatible format first (most common)
        return $this->sendToOpenAI($prompt, $setting, $options);
    }
}
