<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AiIntegrationController extends Controller
{
    public function index()
    {
        $aiSetting = AiSetting::first();
        return view('backend.integrations.ai', compact('aiSetting'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1',
            'default_provider' => 'required|string|in:openai,gemini,groq',
            'fallback_provider' => 'nullable|string|in:openai,gemini,groq',
            'system_prompt' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens' => 'nullable|integer|min:1|max:32768',
            'request_timeout' => 'required|integer|min:5|max:300',
            'openai_model' => 'nullable|string',
            'gemini_model' => 'nullable|string',
            'groq_model' => 'nullable|string',
            'openai_api_key' => 'nullable|string',
            'gemini_api_key' => 'nullable|string',
            'groq_api_key' => 'nullable|string',
            'clear_openai_key' => 'nullable|boolean',
            'clear_gemini_key' => 'nullable|boolean',
            'clear_groq_key' => 'nullable|boolean',
        ]);

        $existing = AiSetting::first();

        $openaiKey = $this->resolveSecret(
            $request->input('openai_api_key'),
            $request->boolean('clear_openai_key'),
            $existing?->openai_api_key
        );

        $geminiKey = $this->resolveSecret(
            $request->input('gemini_api_key'),
            $request->boolean('clear_gemini_key'),
            $existing?->gemini_api_key
        );

        $groqKey = $this->resolveSecret(
            $request->input('groq_api_key'),
            $request->boolean('clear_groq_key'),
            $existing?->groq_api_key
        );

        AiSetting::updateOrCreate(
            ['id' => $id],
            [
                'status' => (int) $request->status,
                'default_provider' => $request->default_provider,
                'fallback_provider' => $request->fallback_provider ?: null,
                'system_prompt' => $request->system_prompt,
                'temperature' => $request->temperature !== null ? (float) $request->temperature : null,
                'max_tokens' => $request->max_tokens !== null ? (int) $request->max_tokens : null,
                'request_timeout' => (int) $request->request_timeout,
                'openai_api_key' => $openaiKey,
                'openai_model' => $request->openai_model,
                'gemini_api_key' => $geminiKey,
                'gemini_model' => $request->gemini_model,
                'groq_api_key' => $groqKey,
                'groq_model' => $request->groq_model,
            ]
        );

        Toastr::success('AI settings updated successfully!');
        return redirect()->back();
    }

    private function resolveSecret(?string $incoming, bool $clear, ?string $existing): ?string
    {
        if ($clear) {
            return null;
        }

        $incoming = trim((string) $incoming);
        if ($incoming !== '') {
            return Crypt::encryptString($incoming);
        }

        return $existing;
    }
}
