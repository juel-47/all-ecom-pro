<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Log In - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
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
        
        <!-- Brand/Logo -->
        <div class="mb-12 text-center">
            <a href="/" class="inline-flex flex-col items-center gap-5 group">
                <div class="w-20 h-20 rounded-3xl bg-linear-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center shadow-indigo-500/20 shadow-2xl group-hover:scale-105 transition-all duration-700 border border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-11 h-11 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-black tracking-tight bg-clip-text text-transparent bg-linear-to-b from-white to-slate-500">
                    {{ config('app.name', 'Danish Gift') }}
                </h1>
            </a>
        </div>

        <!-- Login Card -->
        <div class="w-full max-w-md glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
                <p class="text-slate-500 text-sm font-medium">Please sign in to your account</p>
            </div>

            <x-auth-session-status class="mb-6 text-center text-emerald-400 text-sm font-bold" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-8 relative z-10">
                @csrf

                <div class="space-y-3">
                    <label for="email" class="text-xs font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="w-full pl-14 pr-6 py-5 bg-slate-900/60 border border-slate-800 text-white rounded-[1.25rem] focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/40 placeholder-slate-700 transition-all outline-none"
                            placeholder="name@example.com">
                    </div>
                    @if ($errors->has('email'))
                        <p class="text-rose-500 text-[11px] font-bold mt-2 ml-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="space-y-3">
                    <label for="password" class="text-xs font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-14 pr-6 py-5 bg-slate-900/60 border border-slate-800 text-white rounded-[1.25rem] focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/40 placeholder-slate-700 transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-[11px] font-bold ml-1" />
                </div>

                <div class="flex items-center justify-between px-1">
                    <label for="remember_me" class="flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                        <div class="w-5 h-5 border border-slate-700 rounded-lg bg-slate-900/60 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="ml-3 text-xs text-slate-500 font-bold group-hover:text-slate-400 transition-colors uppercase tracking-widest">Remember me</span>
                    </label>
                    <a class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors uppercase tracking-widest" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-5 px-4 border border-indigo-500/10 text-sm font-black rounded-[1.25rem] text-white bg-indigo-600 hover:bg-indigo-500 transition-all duration-300 shadow-indigo-600/30 shadow-2xl hover:-translate-y-1 active:translate-y-0">
                        Sign In
                    </button>
                </div>

                <div class="mt-12 text-center pt-8 border-t border-slate-800/50">
                    <p class="text-slate-600 text-xs font-bold uppercase tracking-widest">
                        New here? 
                        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 ml-2">Create Account</a>
                    </p>
                </div>
            </form>
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
