@extends('layouts.app')
@section('title', 'Belum ada kelompok')
@section('crumb', 'Belum ada kelompok')

@section('content')
    <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-10 text-center">
        <div class="mx-auto w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
            <x-icon name="users" class="w-7 h-7" />
        </div>
        <h2 class="text-xl font-bold text-slate-900">Belum ada kelompok tutorial</h2>
        <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">
            Akun Anda belum memiliki kelompok pada semester yang sedang aktif. Hubungi admin LPIDB
            atau buat kelompok melalui <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">php artisan lpidb:tambah-tutor</code>.
        </p>
    </section>
@endsection
