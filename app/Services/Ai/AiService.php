<?php

namespace App\Services\Ai;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AiService
{
    public function generate(string $prompt, ?string $provider = null, array $options = []): string
    {
        $setting = AiSetting::first();
        if (!$setting || !$setting->status) {
            throw new \RuntimeException('AI is disabled.');
        }

        $provider = strtolower($provider ?: $setting->default_provider ?: 'openai');
        $fallback = $setting->fallback_provider ? strtolower($setting->fallback_provider) : null;

        try {
            return $this->dispatch($provider, $prompt, $setting, $options);
        } catch (\Throwable $e) {
            if ($fallback && $fallback !== $provider) {
                return $this->dispatch($fallback, $prompt, $setting, $options);
            }

            throw $e;
        }
    }

    private function dispatch(string $provider, string $prompt, AiSetting $setting, array $options): string
    {
        return match ($provider) {
            'openai' => $this->callOpenAi($prompt, $setting, $options),
            'gemini' => $this->callGemini($prompt, $setting, $options),
            'groq' => $this->callGroq($prompt, $setting, $options),
            default => throw new \InvalidArgumentException('Unsupported AI provider: ' . $provider),
        };
    }

    private function callOpenAi(string $prompt, AiSetting $setting, array $options): string
    {
        $apiKey = $this->decryptKey($setting->openai_api_key);
        if (!$apiKey) {
            throw new \RuntimeException('OpenAI API key is not set.');
        }

        $model = $options['model'] ?? $setting->openai_model ?? 'gpt-4o-mini';
        $temperature = $options['temperature'] ?? $setting->temperature ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? $setting->max_tokens;
        $timeout = $options['timeout'] ?? $setting->request_timeout ?? 60;

        $messages = $this->buildMessages($prompt, $options['system_prompt'] ?? $setting->system_prompt);

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) $temperature,
        ];

        if ($maxTokens) {
            $payload['max_tokens'] = (int) $maxTokens;
        }

        $response = Http::timeout($timeout)
            ->withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', $payload);

        $data = $response->throw()->json();
        $text = data_get($data, 'choices.0.message.content');

        if (!$text) {
            throw new \RuntimeException('OpenAI returned an empty response.');
        }

        return trim((string) $text);
    }

    private function callGroq(string $prompt, AiSetting $setting, array $options): string
    {
        $apiKey = $this->decryptKey($setting->groq_api_key);
        if (!$apiKey) {
            throw new \RuntimeException('Groq API key is not set.');
        }

        $model = $options['model'] ?? $setting->groq_model ?? 'llama-3.1-8b-instant';
        $temperature = $options['temperature'] ?? $setting->temperature ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? $setting->max_tokens;
        $timeout = $options['timeout'] ?? $setting->request_timeout ?? 60;

        $messages = $this->buildMessages($prompt, $options['system_prompt'] ?? $setting->system_prompt);

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) $temperature,
        ];

        if ($maxTokens) {
            $payload['max_tokens'] = (int) $maxTokens;
        }

        $endpoint = 'https://api.groq.com/openai/v1/chat/completions';
        $response = Http::timeout($timeout)
            ->withToken($apiKey)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            $error = $response->json('error') ?? [];
            $message = is_array($error) ? (string) ($error['message'] ?? '') : '';
            $code = is_array($error) ? (string) ($error['code'] ?? '') : '';

            if (($code === 'model_decommissioned' || str_contains(strtolower($message), 'decommissioned'))
                && $model !== 'llama-3.1-8b-instant') {
                $payload['model'] = 'llama-3.1-8b-instant';
                $response = Http::timeout($timeout)
                    ->withToken($apiKey)
                    ->post($endpoint, $payload);
            }
        }

        $data = $response->throw()->json();
        $text = data_get($data, 'choices.0.message.content');

        if (!$text) {
            throw new \RuntimeException('Groq returned an empty response.');
        }

        return trim((string) $text);
    }

    private function callGemini(string $prompt, AiSetting $setting, array $options): string
    {
        $apiKey = $this->decryptKey($setting->gemini_api_key);
        if (!$apiKey) {
            throw new \RuntimeException('Gemini API key is not set.');
        }

        $model = $options['model'] ?? $setting->gemini_model ?? 'gemini-2.5-flash';
        $temperature = $options['temperature'] ?? $setting->temperature ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? $setting->max_tokens;
        $timeout = $options['timeout'] ?? $setting->request_timeout ?? 60;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ];

        $systemPrompt = $options['system_prompt'] ?? $setting->system_prompt;
        if ($systemPrompt) {
            $payload['system_instruction'] = [
                'parts' => [
                    ['text' => $systemPrompt],
                ],
            ];
        }

        $generationConfig = [];
        if ($temperature !== null) {
            $generationConfig['temperature'] = (float) $temperature;
        }
        if ($maxTokens) {
            $generationConfig['maxOutputTokens'] = (int) $maxTokens;
        }
        if ($generationConfig) {
            $payload['generationConfig'] = $generationConfig;
        }

        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            $model
        );

        $response = Http::timeout($timeout)
            ->withHeaders(['x-goog-api-key' => $apiKey])
            ->post($url, $payload);

        $data = $response->throw()->json();
        $text = data_get($data, 'candidates.0.content.parts.0.text');

        if (!$text) {
            throw new \RuntimeException('Gemini returned an empty response.');
        }

        return trim((string) $text);
    }

    private function buildMessages(string $prompt, ?string $systemPrompt): array
    {
        $messages = [];
        if ($systemPrompt) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        return $messages;
    }

    private function decryptKey(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}



