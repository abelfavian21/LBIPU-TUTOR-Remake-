@extends('layouts.app')
@section('title', 'Update Password')
@section('crumb', 'Update Password')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Update Password</h1>
        <p class="mt-1 text-sm text-slate-500">Gunakan password minimal 8 karakter yang sulit ditebak.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Lama</label>
            <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            @error('current_password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                <x-icon name="lock" class="w-4 h-4" /> Perbarui Password
            </button>
        </div>
    </form>
</div>
@endsection
