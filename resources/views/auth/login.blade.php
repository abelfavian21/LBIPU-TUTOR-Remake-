<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ETP - LBIPU</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'] },
            boxShadow: { card: '0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 40px rgba(0, 0, 0, 0.08)' }
        } } }
    </script>
    <style>
        body { background: radial-gradient(circle at 50% 50%, #594ee8 0%, #4b3ec7 50%, #4335b8 100%); min-height: 100vh; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 font-sans antialiased selection:bg-indigo-500 selection:text-white">
<main class="w-full flex justify-center py-6">
    <div class="w-full max-w-[420px] bg-white rounded-2xl shadow-card px-8 pt-8 pb-7 flex flex-col">

        <header class="flex items-center gap-3.5 mb-6">
            <div class="w-8 h-8 rounded-full bg-[#1e2b4d] flex items-center justify-center p-1 shadow-sm shrink-0 border border-amber-400/40">
                <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 24 24" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-[17px] font-bold text-slate-800 tracking-tight leading-tight">ETP - LBIPU</h1>
                <p class="text-[12px] text-slate-400 font-normal leading-tight mt-0.5">English Tutorial Program</p>
            </div>
        </header>

        @if (session('info'))
            <div class="mb-3.5 text-xs text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">{{ session('info') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-3.5 text-xs text-rose-700 bg-rose-50 border border-rose-100 rounded-lg px-3 py-2">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-3.5">
            @csrf
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Username / NIM" required autofocus autocomplete="username"
                       class="block w-full pl-10 pr-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all">
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <input id="password" name="password" type="password" placeholder="Password" required autocomplete="current-password"
                       class="block w-full pl-10 pr-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all">
            </div>
            <div class="flex items-center pt-1 pb-1">
                <input id="remember" name="remember" type="checkbox" value="1" checked
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer transition-colors">
                <label for="remember" class="ml-2 block text-xs text-slate-600 font-medium select-none cursor-pointer">Ingat saya di perangkat ini</label>
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-white font-medium text-sm bg-[#4f46e5] hover:bg-[#4338ca] active:scale-[0.99] shadow-sm transition-all duration-150 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Masuk</span>
            </button>
        </form>

        <div class="relative my-4 flex items-center justify-center">
            <div class="w-full border-t border-slate-200"></div>
            <span class="absolute bg-white px-2.5 text-[11px] text-slate-400">atau</span>
        </div>

        <a href="{{ route('login.cas') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 font-medium text-xs transition-colors">
            <svg class="w-3.5 h-3.5 text-slate-800" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            <span class="font-semibold text-slate-800 text-[12px]">Masuk dengan CAS UMS</span>
        </a>

        <footer class="mt-6 text-center">
            <p class="text-[10.5px] leading-relaxed text-slate-500 max-w-[280px] mx-auto">
                &copy; {{ date('Y') }} Lembaga Bahasa dan Ilmu Pengetahuan Umum Universitas Muhammadiyah Surakarta
            </p>
        </footer>
    </div>
</main>
</body>
</html>
