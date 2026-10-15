<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Tutor LBIPU UMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ums: {
                            navy: '#002B49', dark: '#001b2e', blue: '#0B4F8C',
                            lightBlue: '#EBF4FC', gold: '#F4B223', accentGold: '#E6A117'
                        }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @media print { body { height: auto !important; overflow: visible !important; } }
    </style>
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden print:h-auto print:overflow-visible">

{{-- Overlay (mobile) --}}
<div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden print:hidden"></div>

{{-- ===== Sidebar ===== --}}
<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0 transition-transform duration-200 w-72 bg-ums-navy text-slate-200 flex flex-col flex-shrink-0 z-40 shadow-xl border-r border-slate-800 print:hidden">
    <div class="px-6 py-5 border-b border-slate-700/60 flex items-center gap-3.5 bg-ums-dark/40">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr to-ums-gold flex items-center justify-center ">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/96/Logo_UMS_Surakarta.png?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=original" alt="Deskripsi Gambar" class="w-11 h-11 object-contain" />

        </div>
        <div class="leading-tight">
            <h1 class="font-bold text-white text-base tracking-wide flex items-center gap-1.5">
                Tutor LBIPU
                <span class="text-[10px] uppercase font-semibold bg-amber-400 text-slate-900 px-1.5 py-0.5 rounded">v2.0</span>
            </h1>
            </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-sm font-medium">
        <div class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Menu Utama</div>

        <x-nav-link :href="route('dashboard')" icon="users" :active="request()->routeIs('dashboard')" :badge="$jumlahPeserta">Peserta</x-nav-link>
        <x-nav-link :href="route('laporan')" icon="check-square" :active="request()->routeIs('laporan', 'presensi.*', 'nilai.*')">Nilai &amp; Presensi</x-nav-link>
        <x-nav-link :href="route('rekap')" icon="bar-chart" :active="request()->routeIs('rekap')">Rekap Mahasiswa</x-nav-link>
        <x-nav-link :href="route('jurnal.index')" icon="file-text" :active="request()->routeIs('jurnal.*')">Tutor &amp; Jurnal</x-nav-link>

        <div class="pt-6 px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Akun Tutor</div>

        <x-nav-link :href="route('profil.edit')" icon="user" :active="request()->routeIs('profil.*')">Data Diri</x-nav-link>
        <x-nav-link :href="route('password.edit')" icon="lock" :active="request()->routeIs('password.*')">Update Password</x-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-rose-300 hover:text-rose-200 hover:bg-rose-950/40 transition-all duration-150">
                <x-icon name="log-out" class="w-5 h-5 text-rose-400" />
                <span>Keluar</span>
            </button>
        </form>
    </nav>

    <div class="p-4 border-t border-slate-700/60 bg-ums-dark/60">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-ums-blue to-cyan-500 ring-2 ring-white/20 flex items-center justify-center font-bold text-white text-sm">{{ $tutor->inisial }}</div>
            <div class="overflow-hidden">
                <p class="text-xs font-semibold text-white truncate">{{ $tutor->nama_singkat }}</p>
                <p class="text-[11px] text-slate-400">{{ $tutor->nim }} &bull; Tutor</p>
            </div>
        </div>
    </div>
</aside>

{{-- ===== Konten utama ===== --}}
<div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0 print:h-auto print:overflow-visible">
    <header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between z-20 flex-shrink-0 print:hidden">
        <div class="flex items-center gap-3 min-w-0">
            <button id="btn-menu" type="button" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg" aria-label="Menu">
                <x-icon name="menu" class="w-5 h-5" />
            </button>
            <div class="hidden sm:flex items-center gap-3 min-w-0">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Portal Akademik</span>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-medium text-slate-500">Tutor LBIPU</span>
                @hasSection('crumb_mid')
                    <span class="text-slate-300">/</span>
                    <span class="text-xs font-medium text-slate-500 hidden md:inline">@yield('crumb_mid')</span>
                @endif
                <span class="text-slate-300">/</span>
            </div>
            <span class="text-xs font-semibold text-ums-blue bg-blue-50 px-2.5 py-1 rounded-md truncate">@yield('crumb', 'Dashboard')</span>
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
            @if ($semesterAktif)
                <div class="hidden md:flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200/70 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Semester {{ $semesterAktif->nama }}
                </div>
            @endif
            <a href="https://wa.me/" target="_blank" rel="noopener" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors" title="Bantuan LBIPU">
                <x-icon name="help" class="w-5 h-5" />
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 sm:py-7 bg-slate-50/70 print:overflow-visible print:p-0">
        <div class="max-w-7xl mx-auto space-y-6">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200/90 py-3.5 px-4 sm:px-8 text-center sm:flex sm:justify-between text-xs text-slate-500 print:hidden">
        <p>&copy; Copyright <span class="font-semibold text-slate-700">LBIPU Universitas Muhammadiyah Surakarta</span>.</p>
        <p class="mt-1 sm:mt-0">Powered by <span class="text-ums-blue font-semibold">LBIPU UMS &amp; Biro TI UMS</span></p>
    </footer>
</div>

{{-- Toast notifikasi --}}
@if (session('success'))
    <div id="toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-emerald-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg print:hidden">
        <x-icon name="check-circle" class="w-5 h-5" />
        <span>{{ session('success') }}</span>
    </div>
    <script>setTimeout(() => document.getElementById('toast')?.remove(), 4000);</script>
@endif

<script>
    // Sidebar mobile
    const sb = document.getElementById('sidebar'), ov = document.getElementById('overlay');
    const toggle = () => { sb.classList.toggle('-translate-x-full'); ov.classList.toggle('hidden'); };
    document.getElementById('btn-menu')?.addEventListener('click', toggle);
    ov.addEventListener('click', toggle);
</script>
@stack('scripts')
</body>
</html>
