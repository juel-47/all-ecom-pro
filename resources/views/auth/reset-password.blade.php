<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - {{ config('app.name', 'Laravel') }}</title>

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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-black tracking-tight bg-clip-text text-transparent bg-linear-to-b from-white to-slate-500 uppercase">
                    Security
                </h1>
            </a>
            <p class="mt-4 text-slate-500 text-sm max-w-sm mx-auto leading-relaxed font-medium">Create a strong, secure password for your account.</p>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            
            <form method="POST" action="{{ route('password.store') }}" class="space-y-7 relative z-10">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Address -->
                <div class="space-y-3">
                    <label for="email" class="text-xs font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">Email Address</label>
                    <div class="relative group border-slate-700">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus
                            class="w-full pl-14 pr-6 py-5 bg-slate-900/60 border border-slate-800 text-white rounded-[1.25rem] focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/40 transition-all outline-none"
                            placeholder="name@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-3">
                    <label for="password" class="text-xs font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">New Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-14 pr-6 py-5 bg-slate-900/60 border border-slate-800 text-white rounded-[1.25rem] focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/40 transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                    @if ($errors->has('password'))
                        <p class="text-rose-500 text-[11px] font-bold mt-2 ml-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div class="space-y-3">
                    <label for="password_confirmation" class="text-xs font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full pl-14 pr-6 py-5 bg-slate-900/60 border border-slate-800 text-white rounded-[1.25rem] focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/40 transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-5 px-4 border border-indigo-500/10 text-sm font-black rounded-[1.25rem] text-white bg-indigo-600 hover:bg-indigo-500 transition-all duration-300 shadow-indigo-600/30 shadow-2xl hover:-translate-y-1 active:translate-y-0">
                        Update Password
                    </button>
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
