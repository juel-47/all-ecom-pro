<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Email - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.35;
            animation: float 20s infinite alternate ease-in-out;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 80px) scale(1.15); }
        }
    </style>
</head>
<body class="h-full bg-[#030712] text-slate-300 overflow-x-hidden relative selection:bg-indigo-500/40 selection:text-white">

    <!-- Background Layer -->
    <div class="fixed inset-0 bg-[#030712] z-[-2]"></div>
    
    <!-- Animated Blobs -->
    <div class="blob bg-indigo-600/20 w-[600px] h-[600px] rounded-full -top-64 -left-32"></div>
    <div class="blob bg-purple-600/10 w-[500px] h-[500px] rounded-full bottom-0 right-0 translate-x-1/4 translate-y-1/4" style="animation-delay: -7s;"></div>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
        
        <!-- Brand -->
        <div class="mb-12 text-center">
            <a href="/" class="inline-flex flex-col items-center gap-5 group">
                <div class="w-20 h-20 rounded-3xl bg-linear-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center shadow-indigo-500/20 shadow-2xl group-hover:scale-105 transition-all duration-500 border border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-11 h-11 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-black tracking-tight bg-clip-text text-transparent bg-linear-to-b from-white to-slate-500 uppercase">
                    Verification
                </h1>
            </a>
            <p class="mt-4 text-slate-500 text-sm max-w-sm mx-auto leading-relaxed font-medium">Almost there! Please check your inbox to verify your email address.</p>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            
            <div class="text-slate-400 text-sm mb-8 text-center leading-relaxed">
                {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 font-bold text-sm text-emerald-400 text-center bg-emerald-900/20 py-3 px-5 rounded-2xl border border-emerald-500/20">
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif

            <div class="space-y-4 relative z-10">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-5 px-4 border border-indigo-500/10 text-sm font-black rounded-[1.25rem] text-white bg-indigo-600 hover:bg-indigo-500 transition-all duration-300 shadow-indigo-600/30 shadow-2xl hover:-translate-y-1 active:translate-y-0">
                        Resend Code
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest pt-4">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-16 text-center">
            <p class="text-slate-700 text-[10px] font-black tracking-[0.3em] uppercase opacity-60">
                &copy; {{ date('Y') }} {{ config('app.name') }} &bull; All Rights Reserved
            </p>
        </footer>
    </div>
</body>
</html>
