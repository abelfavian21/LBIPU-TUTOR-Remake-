@extends('layouts.app')
@section('title', 'Rekap Mahasiswa')
@section('crumb_mid', 'Nilai & Presensi')
@section('crumb', 'Rekap Mahasiswa')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Rekap Presensi &amp; Nilai</h1>
            <p class="mt-1 text-sm text-slate-500">Jumlah masuk dan rata-rata nilai tiap mahasiswa, dihitung otomatis dari data harian.</p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('rekap.cetak') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                <x-icon name="printer" class="w-4 h-4" /> Cetak Nilai &amp; Presensi
            </a>
            <a href="{{ route('rekap.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <x-icon name="download" class="w-4 h-4 text-emerald-600" /> Export Excel
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Mahasiswa</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $mahasiswas->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pertemuan Terlaksana</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $terlaksana }} <span class="text-sm font-medium text-slate-400">/ {{ $pertemuans->count() }}</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Rata-rata Kehadiran</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $rataHadir !== null ? round($rataHadir).'%' : '-' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Rata-rata Nilai Kelas</p>
            <p class="mt-1 text-2xl font-bold text-ums-blue">{{ $rataNilai !== null ? number_format($rataNilai, 2, ',', '') : '-' }}</p>
        </div>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-semibold">
                    <th class="py-3 px-6 w-14 text-center">No</th>
                    <th class="py-3 px-4 w-36">NIM</th>
                    <th class="py-3 px-4">Nama Mahasiswa</th>
                    <th class="py-3 px-3 text-center">Hadir</th>
                    <th class="py-3 px-3 text-center">Izin</th>
                    <th class="py-3 px-3 text-center">Absen</th>
                    <th class="py-3 px-4 w-44">% Kehadiran</th>
                    <th class="py-3 px-3 text-center">Nilai Terisi</th>
                    <th class="py-3 px-6 text-center">Rata-rata</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach ($mahasiswas as $m)
                    @php $rendah = $m->persen_hadir !== null && $m->persen_hadir < $batas; @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-6 text-center text-xs text-slate-400 font-medium">{{ $loop->iteration }}</td>
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-600">{{ $m->nim }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $m->avatar_class }} font-bold text-xs flex items-center justify-center flex-shrink-0">{{ $m->inisial }}</div>
                                <span class="font-bold text-slate-900 text-[13px]">{{ $m->nama }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-3 text-center font-semibold text-emerald-600">{{ $m->hadir_count }}</td>
                        <td class="py-3.5 px-3 text-center font-semibold text-amber-600">{{ $m->izin_count }}</td>
                        <td class="py-3.5 px-3 text-center font-semibold text-rose-600">{{ $m->absen_count }}</td>
                        <td class="py-3.5 px-4">
                            @if ($m->persen_hadir !== null)
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full {{ $rendah ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $m->persen_hadir }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold w-10 text-right {{ $rendah ? 'text-rose-600' : 'text-slate-700' }}">{{ $m->persen_hadir }}%</span>
                                </div>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 text-center text-slate-500 text-xs">{{ $m->nilai_count }}</td>
                        <td class="py-3.5 px-6 text-center font-bold text-slate-800">{{ $m->rata_nilai !== null ? number_format($m->rata_nilai, 2, ',', '') : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 text-xs text-slate-500">
            % kehadiran = jumlah hadir &divide; pertemuan yang sudah dicatat presensinya. Merah bila di bawah {{ $batas }}%.
        </div>
    </section>
@endsection
