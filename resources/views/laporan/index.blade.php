@extends('layouts.app')
@section('title', 'Nilai & Presensi')
@section('crumb', 'Laporan Kegiatan Tutorial')

@section('content')
    @php
        $persen = fn ($n) => $statistik['total'] ? round($n / $statistik['total'] * 100) : 0;
    @endphp

    {{-- Ringkasan progres --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Progress Presensi</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $statistik['presensi'] }} / {{ $statistik['total'] }}
                        <span class="text-xs font-medium text-emerald-600">{{ $persen($statistik['presensi']) }}% Terisi</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="user-check" /></div>
            </div>
            <div class="mt-3 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-emerald-500" style="width: {{ $persen($statistik['presensi']) }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Input Nilai Sesi</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $statistik['nilai'] }} / {{ $statistik['total'] }}
                        <span class="text-xs font-medium text-blue-600">{{ $statistik['nilai'] === $statistik['total'] ? 'Semua Lengkap' : $persen($statistik['nilai']).'% Terisi' }}</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><x-icon name="award" /></div>
            </div>
            <div class="mt-3 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-blue-600" style="width: {{ $persen($statistik['nilai']) }}%"></div></div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Jurnal Kegiatan</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $statistik['jurnal'] }} Dokumen
                        <span class="text-xs font-medium text-indigo-600">{{ $statistik['jurnal'] === $statistik['total'] ? 'Siap Cetak' : 'Belum lengkap' }}</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><x-icon name="clipboard-check" /></div>
            </div>
            <div class="mt-3 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-indigo-500" style="width: {{ $persen($statistik['jurnal']) }}%"></div></div>
        </div>
    </div>

    {{-- Daftar pertemuan --}}
    <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><x-icon name="list-check" /></div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Jadwal &amp; Status {{ $statistik['total'] }} Pertemuan</h2>
                    <p class="text-xs text-slate-500">Kelola dan perbarui berkas presensi, penilaian, serta jurnal per pertemuan</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('rekap.cetak') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                    <x-icon name="printer" class="w-4 h-4" /> Cetak Nilai &amp; Presensi
                </a>
                <a href="{{ route('jurnal.export') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm transition">
                    <x-icon name="download" class="w-4 h-4" /> Unduh Rekap Jurnal
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-semibold">
                    <th class="py-3 px-6 w-14 text-center">No</th>
                    <th class="py-3 px-4">Pertemuan</th>
                    <th class="py-3 px-4">Status Presensi</th>
                    <th class="py-3 px-4">Penilaian Mahasiswa</th>
                    <th class="py-3 px-4">Jurnal Tutorial</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach ($pertemuans as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-6 text-center text-slate-400 font-medium">{{ $p->nomor }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-start gap-2">
                                <span class="mt-1.5 w-2 h-2 rounded-full {{ $p->presensis_count > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <div>
                                    <p class="font-bold text-slate-900">Pertemuan ke : {{ $p->nomor }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $p->topik }}@if ($p->tanggal) &middot; {{ $p->tanggal->translatedFormat('d M Y') }}@endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <a href="{{ route('presensi.edit', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-100 transition">
                                <x-icon name="edit" class="w-3.5 h-3.5" /> {{ $p->presensis_count > 0 ? 'Ubah Presensi' : 'Isi Presensi' }}
                            </a>
                        </td>
                        <td class="py-4 px-4">
                            <a href="{{ route('nilai.edit', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100 transition">
                                <x-icon name="edit" class="w-3.5 h-3.5" /> {{ $p->nilai_terisi_count > 0 ? 'Ubah Nilai' : 'Isi Nilai' }}
                            </a>
                        </td>
                        <td class="py-4 px-4">
                            <a href="{{ route('jurnal.edit', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100 transition">
                                <x-icon name="file-text" class="w-3.5 h-3.5" /> {{ $p->jurnal ? 'Ubah Jurnal' : 'Isi Jurnal' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
            <span class="flex items-center gap-2"><x-icon name="info" class="w-4 h-4 text-blue-500" /> Perubahan presensi, nilai, dan jurnal langsung tersimpan ke database LPIDB UMS.</span>
            <span class="font-mono">Total: {{ $statistik['total'] }} Pertemuan</span>
        </div>
    </section>
@endsection
