@extends('layouts.app')
@section('title', 'Tutor & Jurnal')
@section('crumb', 'Riwayat Jurnal')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Tutor &amp; Jurnal</h1>
            <p class="mt-1 text-sm text-slate-500">Riwayat jurnal harian tutorial kelompok {{ $kelompok->kode }}.</p>
        </div>
        <a href="{{ route('jurnal.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold shadow-sm self-start">
            <x-icon name="download" class="w-4 h-4" /> Unduh Rekap Jurnal
        </a>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-semibold">
                    <th class="py-3 px-6 w-24">Pekan</th>
                    <th class="py-3 px-4 w-36">Tanggal</th>
                    <th class="py-3 px-4">Materi</th>
                    <th class="py-3 px-4 w-32">Hadir</th>
                    <th class="py-3 px-4">Catatan</th>
                    <th class="py-3 px-6 w-32 text-right">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach ($pertemuans as $p)
                    @php $j = $p->jurnal; @endphp
                    <tr class="hover:bg-slate-50/60 transition align-top">
                        <td class="py-4 px-6 font-bold text-slate-700">Ke-{{ $p->nomor }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ ($j?->tanggal ?? $p->tanggal)?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td class="py-4 px-4">
                            @if ($j)
                                <span class="font-semibold text-slate-900">{{ $j->materi }}</span>
                            @else
                                <span class="text-slate-400">Belum diisi &mdash; {{ $p->topik }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if ($j)
                                <span class="inline-flex text-xs font-semibold rounded-md px-2 py-1 {{ $j->jumlah_hadir >= $totalMahasiswa ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $j->jumlah_hadir }} / {{ $totalMahasiswa }}</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-slate-500 text-xs max-w-xs">{{ $j?->catatan ? \Illuminate\Support\Str::limit($j->catatan, 90) : '-' }}</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('jurnal.edit', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100 transition">
                                <x-icon name="edit" class="w-3.5 h-3.5" /> {{ $j ? 'Ubah' : 'Isi' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
