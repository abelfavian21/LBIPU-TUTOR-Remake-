@extends('layouts.app')
@section('title', 'Jurnal Pertemuan '.$pertemuan->nomor)
@section('crumb_mid', 'Tutor & Jurnal')
@section('crumb', 'Input Jurnal (Pertemuan ke-'.$pertemuan->nomor.')')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Formulir Jurnal Pertemuan
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Input Jurnal Pertemuan</h1>
            <p class="mt-1 text-sm text-slate-500">Laporan pelaksanaan dan evaluasi materi tutorial mahasiswa LPIDB UMS.</p>
        </div>
        <a href="{{ route('jurnal.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 self-start">
            <x-icon name="history" class="w-4 h-4" /> Riwayat Jurnal
        </a>
    </div>

    <form method="POST" action="{{ route('jurnal.update', $pertemuan) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 sm:p-8 space-y-8">
        @csrf
        @method('PUT')

        {{-- Info akademik (read-only) --}}
        <div>
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Informasi Akademik &amp; Tutor</h2>
                    <p class="text-xs text-slate-500">Identitas semester dan profil tutor pelaksana terdaftar.</p>
                </div>
                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 rounded-md px-2.5 py-1">Read-Only</span>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kode Semester</p>
                    <p class="mt-1.5 flex items-center justify-between font-bold text-slate-800">
                        {{ $kelompok->semester->kode }}
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 rounded px-2 py-0.5">{{ $kelompok->semester->nama }}</span>
                    </p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Induk Mahasiswa (NIM)</p>
                    <p class="mt-1.5 font-mono font-bold text-slate-800">{{ auth()->user()->nim }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Tutor</p>
                    <p class="mt-1.5 font-bold text-slate-800">{{ auth()->user()->nama_singkat }}</p>
                </div>
            </div>
        </div>

        {{-- Pelaksanaan --}}
        <div>
            <h2 class="text-base font-bold text-slate-900">Pelaksanaan Tutorial</h2>
            <p class="text-xs text-slate-500">Rincian waktu, frekuensi pertemuan, dan volume kehadiran mahasiswa.</p>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="pilih-pertemuan" class="block text-sm font-semibold text-slate-700 mb-1.5">Pekan / Pertemuan Ke <span class="text-rose-500">*</span></label>
                    <select id="pilih-pertemuan" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($pertemuans as $p)
                            <option value="{{ route('jurnal.edit', $p) }}" @selected($p->id === $pertemuan->id)>
                                Pekan ke-{{ $p->nomor }}{{ $p->nomor === 1 ? ' (Sesi Pembuka)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $form['tanggal']) }}" required
                           class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-5">
                <label for="jumlah_hadir" class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Peserta Hadir <span class="text-rose-500">*</span></label>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <input id="jumlah_hadir" name="jumlah_hadir" type="number" min="0" max="{{ $totalMahasiswa }}" required
                               value="{{ old('jumlah_hadir', $form['jumlah_hadir']) }}"
                               class="w-32 rounded-xl border-slate-300 text-sm pr-12 focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">Mhs</span>
                    </div>
                    <span id="hadir-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-lg px-3 py-2"></span>
                </div>
                @error('jumlah_hadir') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Rincian pembelajaran --}}
        <div>
            <h2 class="text-base font-bold text-slate-900">Rincian Pembelajaran</h2>
            <p class="text-xs text-slate-500">Topik materi yang diajarkan dan ringkasan jalannya tutorial.</p>

            <div class="mt-4 space-y-5">
                <div>
                    <label for="materi" class="block text-sm font-semibold text-slate-700 mb-1.5">Pokok Bahasan / Materi <span class="text-rose-500">*</span></label>
                    <input id="materi" name="materi" type="text" maxlength="255" required value="{{ old('materi', $form['materi']) }}"
                           class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('materi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="catatan" class="block text-sm font-semibold text-slate-700">Catatan Tambahan &amp; Kendala Kelas</label>
                        <span class="text-[11px] text-slate-400">Opsional</span>
                    </div>
                    <textarea id="catatan" name="catatan" rows="4" maxlength="2000"
                              class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $form['catatan']) }}</textarea>
                    @error('catatan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-2 border-t border-slate-100">
            <p class="text-xs text-slate-400 flex items-center gap-1.5 pt-4"><x-icon name="shield" class="w-4 h-4" /> Data jurnal akan tercatat di sistem LPIDB UMS</p>
            <div class="flex gap-2.5 pt-4">
                <a href="{{ route('laporan') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                    <x-icon name="save" class="w-4 h-4" /> Simpan Jurnal
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const total = {{ $totalMahasiswa }};
    const inp = document.getElementById('jumlah_hadir');
    const badge = document.getElementById('hadir-badge');
    function updateBadge() {
        const n = parseInt(inp.value, 10) || 0;
        const lengkap = n === total;
        badge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold rounded-lg px-3 py-2 ' +
            (lengkap ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200');
        badge.textContent = (lengkap ? 'Kehadiran Lengkap: ' : 'Kehadiran: ') + n + ' dari ' + total + ' Mahasiswa';
    }
    inp.addEventListener('input', updateBadge);
    updateBadge();
    document.getElementById('pilih-pertemuan').addEventListener('change', e => { window.location = e.target.value; });
</script>
@endpush
