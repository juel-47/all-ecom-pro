<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <!-- laravel ajax csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset($logoSetting?->favicon ?? '') }}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/summernote/summernote-bs4.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/select2/dist/css/select2.min.css') }}">

    <!-- datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">

    <!-- iconpicker CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap-iconpicker.min.css') }}">

    <!--jq css  -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <!--jq css bootstrap 5 -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css"> --}}

    <!-- Toastr css -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/components.css') }}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
    <style>
        #ai-fab {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #60a5fa, #1e3a8a);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            z-index: 1050;
            box-shadow: 0 16px 28px rgba(30, 58, 138, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        #ai-fab:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(30, 58, 138, 0.45);
        }
        #ai-fab i {
            font-size: 20px;
        }
        .ai-panel {
            position: fixed;
            top: 70px;
            right: 24px;
            width: 440px;
            max-width: calc(100% - 48px);
            height: calc(100% - 120px);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
            display: flex;
            flex-direction: column;
            transform: translateX(120%);
            transition: transform 0.25s ease;
            z-index: 1051;
            overflow: hidden;
        }
        .ai-panel.open {
            transform: translateX(0);
        }
        .ai-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #fff;
        }
        .ai-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        .ai-body {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .ai-msg {
            padding: 10px 12px;
            border-radius: 12px;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 13px;
            line-height: 1.4;
            border: 1px solid transparent;
            max-width: 88%;
            position: relative;
        }
        .ai-msg.user {
            background: #e0e7ff;
            border-color: #c7d2fe;
            align-self: flex-end;
        }
        .ai-msg.ai {
            background: #f1f5f9;
            border-color: #e2e8f0;
            align-self: flex-start;
        }
        .ai-msg.error {
            background: #fee2e2;
            color: #991b1b;
        }
        .ai-msg-time {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            color: #64748b;
        }
        .ai-tools {
            padding: 10px 12px 0 12px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
        }
        .ai-input {
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }
        .ai-input textarea {
            width: 100%;
            resize: none;
            height: 70px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 13px;
        }
        .ai-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .ai-actions button {
            flex: 1;
        }
        .ai-quick {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .ai-quick button {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 16px;
            padding: 4px 10px;
            font-size: 12px;
            color: #1d4ed8;
        }
        .ai-footer-note {
            font-size: 11px;
            color: #64748b;
            margin-top: 6px;
        }
    </style>
    @stack('css')
    <!-- Start GA -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script> --}}
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
    <!-- DataTables CSS for Bootstrap 4 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
</head>

<body>
    @php
        $aiSetting = \Illuminate\Support\Facades\Schema::hasTable('ai_settings')
            ? \App\Models\AiSetting::first()
            : null;
        $aiRoutes = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('ai_settings')) {
            $routeConfig = config('ai_routes.routes', []);
            foreach ($routeConfig as $routeName => $label) {
                if (\Illuminate\Support\Facades\Route::has($routeName)) {
                    $aiRoutes[$routeName] = route($routeName);
                }
            }
        }
    @endphp
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <!-- navbar Content -->
            @include('backend.layouts.navbar')
            <!-- sidebar Content -->
            @include('backend.layouts.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ now()->year }}
                    {{-- <div class="bullet" ></div> <a target="_blank" href="https://inoodex.com/">Developed By Inoodex</a> --}}
                </div>
                <div class="footer-right">
                    <div class="bullet"></div> <a target="_blank" href="https://inoodex.com/">Developed By Inoodex</a>
                </div>
            </footer>
            @if ($aiSetting?->status)
                <div id="ai-fab"><i class="fas fa-robot"></i></div>
                <div id="ai-panel" class="ai-panel" aria-hidden="true">
                    <div class="ai-header">
                        <div class="ai-title"><i class="fas fa-robot"></i> AI Assistant</div>
                        <button type="button" class="btn btn-sm btn-light" id="ai-close">Close</button>
                    </div>
                    <div class="ai-body" id="ai-messages"></div>
                    <div class="ai-tools">
                        <div class="row">
                            <div class="col-6">
                                <label class="small mb-1">Provider</label>
                                <select class="form-control form-control-sm" id="ai-provider">
                                    <option value="openai">OpenAI</option>
                                    <option value="gemini">Gemini</option>
                                    <option value="groq">Groq</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small mb-1">Model (optional)</label>
                                <input type="text" class="form-control form-control-sm" id="ai-model" list="ai-models" placeholder="Use default">
                                <datalist id="ai-models">
                                    <option value="gpt-4o-mini"></option>
                                    <option value="gpt-4o"></option>
                                    <option value="gemini-2.5-flash"></option>
                                    <option value="gemini-2.5-pro"></option>
                                    <option value="llama-3.1-8b-instant"></option>
                                    <option value="llama-3.3-70b-versatile"></option>
                                </datalist>
                            </div>
                        </div>
                        <div class="ai-quick">
                            <button type="button" class="btn btn-sm" data-ai-prompt="Write a product title and short description.">Product</button>
                            <button type="button" class="btn btn-sm" data-ai-prompt="Write a professional customer reply.">Support</button>
                            <button type="button" class="btn btn-sm" data-ai-prompt="Generate SEO keywords and meta description.">SEO</button>
                        </div>
                        <div class="ai-footer-note">History is saved locally in this browser.</div>
                    </div>
                    <div class="ai-input">
                        <textarea id="ai-input" placeholder="Type your request..."></textarea>
                        <div class="ai-actions">
                            <button type="button" class="btn btn-primary btn-sm" id="ai-send">Send</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="ai-clear-input">Clear Input</button>
                            <button type="button" class="btn btn-light btn-sm" id="ai-clear">Clear Chat</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('backend/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/popper.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('backend/assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/chart.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('backend/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- jq js -->
    <script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <!-- jq js bootstrap 5 -->
    {{-- <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script> --}}

    <!-- Toastr css -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Sweet Alert Js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- iconpicker js -->
    {{-- <script src="{{asset('backend/assets/js/bootstrap-iconpicker.min.js')}}"></script> --}}
    <script src="{{ asset('backend/assets/js/bootstrap-iconpicker.bundle.min.js') }}"></script>

    <!-- datetimepicker js -->
    <script src="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Select2  js -->
    <script src="{{ asset('backend/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{asset('backend/assets/js/page/index-0.js')}}"></script> --}}
    <!-- Template JS File -->
    <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>
    {!! Toastr::message() !!}
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}")
            @endforeach
        @endif
        // Show success message
        // @if (session('success'))
        //     toastr.success("{{ session('success') }}");
        // @endif

        // // (Optional) Show other types
        // @if (session('error'))
        //     toastr.error("{{ session('error') }}");
        // @endif

        // @if (session('warning'))
        //     toastr.warning("{{ session('warning') }}");
        // @endif

        // @if (session('info'))
        //     toastr.info("{{ session('info') }}");
        // @endif
    </script>

      <!-- Datatables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


    <!-- Dynamic Delete alert -->
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.delete-item', function(event) {
                event.preventDefault();

                let deletUrl = $(this).attr('href');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                    focusConfirm: false,
                    focusCancel: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: deletUrl,
                            success: function(data) {
                                if (data.status == 'success') {
                                    Swal.fire(
                                        'Deleted',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        window.location.reload();
                                    });
                                } else if (data.status == 'error') {
                                    Swal.fire(
                                        "Can't Delete!",
                                        data.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        });
                    }
                });
            });
        });
    </script>

    @if ($aiSetting?->status)
        <script>
            (function() {
                const panel = document.getElementById('ai-panel');
                const fab = document.getElementById('ai-fab');
                const closeBtn = document.getElementById('ai-close');
                const sendBtn = document.getElementById('ai-send');
                const clearInputBtn = document.getElementById('ai-clear-input');
                const clearBtn = document.getElementById('ai-clear');
                const input = document.getElementById('ai-input');
                const messages = document.getElementById('ai-messages');
                const providerSelect = document.getElementById('ai-provider');
                const modelInput = document.getElementById('ai-model');

                if (!panel || !fab) {
                    return;
                }

                const chatUrl = "{{ route('admin.ai.chat') }}";
                const aiRoutes = @json($aiRoutes);
                const defaultProvider = "{{ $aiSetting?->default_provider ?? 'openai' }}";
                const currentRouteName = "{{ \Illuminate\Support\Facades\Route::currentRouteName() ?? '' }}";
                const currentRouteUrl = "{{ url()->current() }}";
                providerSelect.value = defaultProvider;

                function runPendingActions() {
                    const stored = localStorage.getItem('ai_pending_actions');
                    if (!stored) {
                        return;
                    }
                    try {
                        const actions = JSON.parse(stored);
                        localStorage.removeItem('ai_pending_actions');
                        runActions(Array.isArray(actions) ? actions : []);
                    } catch (e) {
                        localStorage.removeItem('ai_pending_actions');
                    }
                }

                function runActions(actions) {
                    if (!Array.isArray(actions) || actions.length === 0) {
                        return;
                    }

                    let navigateAction = actions.find(action => action.type === 'navigate');
                    if (navigateAction) {
                        const url = resolveRouteUrl(navigateAction);
                        const remaining = actions.filter(action => action.type !== 'navigate');
                        if (remaining.length > 0) {
                            localStorage.setItem('ai_pending_actions', JSON.stringify(remaining));
                        }
                        if (url) {
                            window.location.href = url;
                            return;
                        }
                    }

                    actions.forEach(action => {
                        switch (action.type) {
                            case 'fill':
                                fillField(action);
                                break;
                            case 'click':
                                clickElement(action);
                                break;
                            case 'submit':
                                submitForm(action);
                                break;
                            default:
                                break;
                        }
                    });
                }

                function resolveRouteUrl(action) {
                    if (action && action.route_key && aiRoutes[action.route_key]) {
                        return aiRoutes[action.route_key];
                    }
                    if (action && action.url) {
                        return action.url;
                    }
                    return null;
                }

                function fillField(action) {
                    const field = action.field || '';
                    const selector = action.selector || (field ? `[name="${field}"]` : '');
                    if (!selector) {
                        return;
                    }
                    const element = document.querySelector(selector);
                    if (!element) {
                        return;
                    }
                    const value = action.value ?? '';
                    const tag = element.tagName.toLowerCase();

                    if (element.type === 'checkbox') {
                        element.checked = value === true || value === 1 || value === '1' || value === 'true';
                        element.dispatchEvent(new Event('change', { bubbles: true }));
                        return;
                    }

                    if (tag === 'select') {
                        let selected = false;
                        const options = Array.from(element.options || []);
                        const raw = value !== null && value !== undefined ? String(value) : '';
                        const byValue = options.find(opt => opt.value === raw);
                        if (byValue) {
                            element.value = byValue.value;
                            selected = true;
                        } else if (raw !== '') {
                            const byText = options.find(opt => opt.text.trim().toLowerCase() === raw.trim().toLowerCase());
                            if (byText) {
                                element.value = byText.value;
                                selected = true;
                            }
                        }
                        if (!selected && raw !== '') {
                            element.value = raw;
                        }
                        element.dispatchEvent(new Event('change', { bubbles: true }));
                        return;
                    }

                    if (tag === 'input' || tag === 'textarea') {
                        if (element.type === 'file') {
                            return;
                        }
                        if (element.classList.contains('summernote') && typeof $(element).summernote === 'function') {
                            $(element).summernote('code', value);
                        } else {
                            element.value = value;
                            element.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    }
                }

                function clickElement(action) {
                    const selector = action.selector || '';
                    if (!selector) {
                        return;
                    }
                    const element = document.querySelector(selector);
                    if (element) {
                        element.click();
                    }
                }

                function submitForm(action) {
                    const selector = action.selector || 'form';
                    const form = document.querySelector(selector);
                    if (form) {
                        form.submit();
                    }
                }

                function togglePanel(show) {
                    if (show) {
                        panel.classList.add('open');
                        panel.setAttribute('aria-hidden', 'false');
                    } else {
                        panel.classList.remove('open');
                        panel.setAttribute('aria-hidden', 'true');
                    }
                }

                function formatTime(ts) {
                    const date = new Date(ts);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }

                function addMessage(text, type, ts) {
                    const item = document.createElement('div');
                    item.className = 'ai-msg ' + type;
                    item.textContent = text;
                    const time = document.createElement('span');
                    time.className = 'ai-msg-time';
                    time.textContent = formatTime(ts || Date.now());
                    item.appendChild(time);
                    messages.appendChild(item);
                    messages.scrollTop = messages.scrollHeight;
                }

                function loadHistory() {
                    const stored = localStorage.getItem('ai_chat_history');
                    if (!stored) {
                        return [];
                    }
                    try {
                        const parsed = JSON.parse(stored);
                        if (!Array.isArray(parsed)) {
                            return [];
                        }
                        parsed.forEach(function(item) {
                            if (item && item.role && item.content) {
                                addMessage(item.content, item.role === 'assistant' ? 'ai' : 'user', item.ts || Date.now());
                            }
                        });
                        return parsed;
                    } catch (e) {
                        return [];
                    }
                }

                function saveHistory(history) {
                    localStorage.setItem('ai_chat_history', JSON.stringify(history.slice(-10)));
                }

                function setSending(sending) {
                    sendBtn.disabled = sending;
                    sendBtn.textContent = sending ? 'Sending...' : 'Send';
                }

                let history = loadHistory();

                function sendMessage() {
                    const message = input.value.trim();
                    if (!message) {
                        return;
                    }

                    const userTs = Date.now();
                    addMessage(message, 'user', userTs);
                    history.push({ role: 'user', content: message, ts: userTs });
                    input.value = '';

                    const payload = {
                        message: message,
                        provider: providerSelect.value,
                        model: modelInput.value.trim(),
                        route_name: currentRouteName,
                        route_url: currentRouteUrl,
                        page_title: getPageTitle(),
                        fields: getFieldNames(),
                        history: history.slice(-6)
                    };

                    setSending(true);
                    $.ajax({
                        url: chatUrl,
                        method: 'POST',
                        data: payload,
                        success: function(res) {
                            if (res && res.status === 'success') {
                                const aiTs = Date.now();
                                addMessage(res.reply, 'ai', aiTs);
                                history.push({ role: 'assistant', content: res.reply, ts: aiTs });
                                saveHistory(history);
                                if (res.actions && Array.isArray(res.actions)) {
                                    runActions(res.actions);
                                }
                            } else {
                                addMessage(res.message || 'AI error', 'error');
                            }
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : 'AI request failed.';
                            addMessage(msg, 'error');
                        },
                        complete: function() {
                            setSending(false);
                        }
                    });
                }

                fab.addEventListener('click', function() {
                    togglePanel(true);
                });
                closeBtn.addEventListener('click', function() {
                    togglePanel(false);
                });
                sendBtn.addEventListener('click', sendMessage);
                clearInputBtn.addEventListener('click', function() {
                    input.value = '';
                    input.focus();
                });
                clearBtn.addEventListener('click', function() {
                    messages.innerHTML = '';
                    input.value = '';
                    history = [];
                    localStorage.removeItem('ai_chat_history');
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                document.querySelectorAll('[data-ai-prompt]').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        input.value = btn.getAttribute('data-ai-prompt');
                        input.focus();
                    });
                });

                runPendingActions();

                function getPageTitle() {
                    const cardHeader = document.querySelector('.card-header h4');
                    if (cardHeader && cardHeader.textContent) {
                        return cardHeader.textContent.trim();
                    }
                    const header = document.querySelector('.section-header h1');
                    if (header && header.textContent) {
                        return header.textContent.trim();
                    }
                    return '';
                }

                function getFieldNames() {
                    const fields = [];
                    const container = document.querySelector('.main-content') || document;
                    container.querySelectorAll('input[name], textarea[name], select[name]').forEach(function(el) {
                        const name = el.getAttribute('name');
                        if (!name || name.includes('[')) {
                            return;
                        }
                        if (!fields.includes(name)) {
                            fields.push(name);
                        }
                    });
                    return fields;
                }
            })();
        </script>
    @endif

    @stack('scripts')

</body>

</html>
