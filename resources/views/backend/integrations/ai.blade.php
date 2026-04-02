@extends('backend.layouts.master')
@section('title', 'AI API')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>AI API</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('admin.integrations.ai.update', $aiSetting?->id ?? 1) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Overview</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-light mb-4">
                                    This is a global AI integration. One company-level API key is used for all users.
                                    Keys are stored encrypted.
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>AI Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1" {{ (int)($aiSetting?->status ?? 0) === 1 ? 'selected' : '' }}>Enable</option>
                                            <option value="0" {{ (int)($aiSetting?->status ?? 0) === 0 ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Default Provider</label>
                                        @php $defaultProvider = $aiSetting?->default_provider ?? 'openai'; @endphp
                                        <select class="form-control" name="default_provider">
                                            <option value="openai" {{ $defaultProvider === 'openai' ? 'selected' : '' }}>OpenAI (ChatGPT)</option>
                                            <option value="gemini" {{ $defaultProvider === 'gemini' ? 'selected' : '' }}>Gemini (Google)</option>
                                            <option value="groq" {{ $defaultProvider === 'groq' ? 'selected' : '' }}>Groq (OpenAI-compatible)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fallback Provider</label>
                                        @php $fallbackProvider = $aiSetting?->fallback_provider ?? ''; @endphp
                                        <select class="form-control" name="fallback_provider">
                                            <option value="">None</option>
                                            <option value="openai" {{ $fallbackProvider === 'openai' ? 'selected' : '' }}>OpenAI</option>
                                            <option value="gemini" {{ $fallbackProvider === 'gemini' ? 'selected' : '' }}>Gemini</option>
                                            <option value="groq" {{ $fallbackProvider === 'groq' ? 'selected' : '' }}>Groq</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Request Timeout (sec)</label>
                                        <input type="number" class="form-control" name="request_timeout"
                                            value="{{ $aiSetting?->request_timeout ?? 60 }}" min="5" max="300">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>System Prompt (Global)</label>
                                        <textarea class="form-control" name="system_prompt" rows="3"
                                            placeholder="You are a helpful assistant.">{{ $aiSetting?->system_prompt ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Temperature</label>
                                        <input type="number" step="0.1" min="0" max="2" class="form-control" name="temperature"
                                            value="{{ $aiSetting?->temperature ?? 0.7 }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Max Tokens</label>
                                        <input type="number" class="form-control" name="max_tokens"
                                            value="{{ $aiSetting?->max_tokens ?? 1024 }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>OpenAI (ChatGPT)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>OpenAI API Key</label>
                                        <input type="password" class="form-control" name="openai_api_key" placeholder="sk-...">
                                        <small class="text-muted">
                                            Status: {{ $aiSetting?->openai_api_key ? 'Configured' : 'Not Set' }}
                                        </small>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="clear_openai_key" value="1" id="clearOpenaiKey">
                                            <label class="form-check-label" for="clearOpenaiKey">Clear OpenAI Key</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>OpenAI Model</label>
                                        <input type="text" class="form-control" name="openai_model"
                                            value="{{ $aiSetting?->openai_model ?? 'gpt-4o-mini' }}" placeholder="gpt-4o-mini">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Gemini (Google)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Gemini API Key</label>
                                        <input type="password" class="form-control" name="gemini_api_key" placeholder="AIza...">
                                        <small class="text-muted">
                                            Status: {{ $aiSetting?->gemini_api_key ? 'Configured' : 'Not Set' }}
                                        </small>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="clear_gemini_key" value="1" id="clearGeminiKey">
                                            <label class="form-check-label" for="clearGeminiKey">Clear Gemini Key</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gemini Model</label>
                                        <input type="text" class="form-control" name="gemini_model"
                                            value="{{ $aiSetting?->gemini_model ?? 'gemini-2.5-flash' }}" placeholder="gemini-2.5-flash">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Groq (OpenAI-compatible)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Groq API Key</label>
                                        <input type="password" class="form-control" name="groq_api_key" placeholder="gsk_...">
                                        <small class="text-muted">
                                            Status: {{ $aiSetting?->groq_api_key ? 'Configured' : 'Not Set' }}
                                        </small>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="clear_groq_key" value="1" id="clearGroqKey">
                                            <label class="form-check-label" for="clearGroqKey">Clear Groq Key</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Groq Model</label>
                                        <input type="text" class="form-control" name="groq_model"
                                            value="{{ $aiSetting?->groq_model ?? 'llama-3.1-8b-instant' }}" placeholder="llama-3.1-8b-instant">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update AI Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

