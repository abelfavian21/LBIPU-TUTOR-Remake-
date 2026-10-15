@extends('layouts.app')
@section('title', 'Data Diri')
@section('crumb', 'Data Diri')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Data Diri Tutor</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui nama, nomor telepon, dan email Anda. Username &amp; NIM hanya bisa diubah oleh admin.</p>
    </div>

    <form method="POST" action="{{ route('profil.update') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <input type="text" value="{{ $user->username }}" disabled class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIM</label>
                <input type="text" value="{{ $user->nim }}" disabled class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-mono text-slate-500">
            </div>
        </div>

        <div>
            <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input id="nama" name="nama" type="text" value="{{ old('nama', $user->nama) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="no_telp" class="block text-sm font-semibold text-slate-700 mb-1.5">No Telepon</label>
                <input id="no_telp" name="no_telp" type="text" value="{{ old('no_telp', $user->no_telp) }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('no_telp') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                <x-icon name="save" class="w-4 h-4" /> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
