@extends('layouts.app')
@section('title', 'Nilai Pertemuan '.$pertemuan->nomor)
@section('crumb_mid', 'Nilai & Presensi')
@section('crumb', 'Input Nilai Mahasiswa (Pertemuan ke-'.$pertemuan->nomor.')')

@section('content')
@php $min = config('lpidb.nilai_min', 1); $max = config('lpidb.nilai_max', 5); @endphp
<form method="POST" action="{{ route('nilai.update', $pertemuan) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Input Nilai Tutorial
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Input Nilai Mahasiswa</h1>
            <p class="mt-1 text-sm text-slate-500 max-w-2xl">Penilaian performa tutorial mahasiswa skala {{ $min }} - {{ $max }} untuk Pertemuan ke-{{ $pertemuan->nomor }}. Masukkan nilai langsung pada kolom yang tersedia.</p>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md shadow-blue-600/25 transition active:scale-95 print:hidden">
            <x-icon name="save" class="w-4 h-4" /> Simpan Nilai
        </button>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><x-icon name="file-text" /></div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 leading-tight">Daftar Mahasiswa Kelompok {{ $kelompok->kode }}</h2>
                    <p class="text-xs text-slate-500">Program Studi {{ $kelompok->prodi }} &bull; Semester {{ $kelompok->semester->nama }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <select id="pilih-pertemuan" class="rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500 print:hidden">
                    @foreach ($pertemuans as $p)
                        <option value="{{ route('nilai.edit', $p) }}" @selected($p->id === $pertemuan->id)>Pertemuan ke-{{ $p->nomor }}</option>
                    @endforeach
                </select>
                <span class="text-xs text-slate-500 bg-slate-100 rounded-lg px-3 py-2">Skala nilai: <b>{{ $min }}</b> (Sangat Kurang) s/d <b>{{ $max }}</b> (Sangat Baik)</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-6 mt-4 text-xs text-rose-700 bg-rose-50 border border-rose-100 rounded-lg px-3 py-2">{{ $errors->first() }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                <tr class="bg-slate-50/75 text-slate-400 uppercase text-[11px] font-bold tracking-wider">
                    <th class="py-3.5 px-6 w-16">No</th>
                    <th class="py-3.5 px-4 w-40">NIM</th>
                    <th class="py-3.5 px-4">Nama Mahasiswa</th>
                    <th class="py-3.5 px-6 text-right">Input Nilai ({{ $min }} - {{ $max }})</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                @foreach ($mahasiswas as $m)
                    @php $v = old("nilai.{$m->id}", $nilai[$m->id] ?? ''); @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-6 text-xs font-semibold text-slate-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-600">{{ $m->nim }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $m->avatar_class }} font-bold text-xs flex items-center justify-center flex-shrink-0">{{ $m->inisial }}</div>
                                <div>
                                    <p class="font-bold text-slate-900 text-[13px]">{{ $m->nama }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $m->prodi }} &bull; {{ $kelompok->kelas }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <input type="number" name="nilai[{{ $m->id }}]" value="{{ $v }}" min="{{ $min }}" max="{{ $max }}" step="1" inputmode="numeric"
                                   class="nilai-input w-16 text-center font-bold text-slate-800 rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <p class="text-xs text-slate-400 flex items-center gap-1.5">
            <x-icon name="clock" class="w-4 h-4" />
            Terakhir disimpan: {{ $terakhirDisimpan ? $terakhirDisimpan->translatedFormat('d M Y, H:i').' WIB' : 'belum pernah' }}
        </p>
        <div class="flex gap-2.5 print:hidden">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <x-icon name="printer" class="w-4 h-4" /> Cetak Form Nilai
            </button>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                <x-icon name="save" class="w-4 h-4" /> Simpan Perubahan Nilai
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('pilih-pertemuan').addEventListener('change', e => { window.location = e.target.value; });
    // Nilai <= 2 ditandai merah, kosong = netral
    const tandai = el => {
        const v = parseInt(el.value, 10);
        el.classList.toggle('!border-rose-400', !isNaN(v) && v <= 2);
        el.classList.toggle('!text-rose-600', !isNaN(v) && v <= 2);
    };
    document.querySelectorAll('.nilai-input').forEach(el => { tandai(el); el.addEventListener('input', () => tandai(el)); });
</script>
@endpush
