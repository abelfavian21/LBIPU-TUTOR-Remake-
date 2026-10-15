@extends('layouts.app')
@section('title', 'Daftar Peserta')
@section('crumb', 'Daftar Peserta')

@section('content')
    {{-- Hero: info tutor & kelompok --}}
    <section class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-gradient-to-br from-blue-50 to-amber-50/50 pointer-events-none"></div>
        <div class="absolute right-40 -bottom-16 w-40 h-40 rounded-full bg-slate-50 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-ums-navy to-ums-blue text-white flex items-center justify-center font-bold text-xl shadow-md shadow-blue-900/10 flex-shrink-0 ring-4 ring-blue-50">
                    {{ substr($tutor->nim, 0, 3) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Peserta Tutorial</h2>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-md bg-amber-100 text-amber-800 border border-amber-200">Kelas {{ $kelompok->kelas }}</span>
                    </div>
                    <div class="mt-2.5 flex flex-wrap items-center gap-y-2 gap-x-6 text-sm">
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-slate-400">Nama Tutor:</span>
                            <span class="font-semibold text-slate-900">{{ $tutor->nama }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-slate-400">NIM Tutor:</span>
                            <span class="font-mono font-medium text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $tutor->nim }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-slate-400">Grup:</span>
                            <span class="font-bold text-ums-blue bg-blue-50 border border-blue-100 px-2 py-0.5 rounded text-xs">{{ $kelompok->kode }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $total = $mahasiswas->count();
                $aktif = $mahasiswas->where('status', 'aktif')->count();
            @endphp
            <div class="flex items-center gap-3 border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100">
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <span class="block text-2xl font-bold text-slate-800">{{ $total }}</span>
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Mahasiswa</span>
                </div>
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <span class="block text-2xl font-bold text-emerald-600">{{ $total ? round($aktif / $total * 100) : 0 }}%</span>
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Status Aktif</span>
                </div>
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <span class="block text-2xl font-bold text-ums-blue">{{ substr($kelompok->kode, 0, 4) }}</span>
                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Kode Prodi</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel peserta --}}
    <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <x-icon name="search" class="w-4 h-4" />
                </div>
                <input id="search-input" type="text" placeholder="Cari berdasarkan nama atau NIM..."
                       class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-ums-blue focus:border-transparent transition-all placeholder:text-slate-400">
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('peserta.export') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                    <x-icon name="download" class="w-4 h-4 text-emerald-600" />
                    Export Excel
                </a>
                @if ($kelompok->wa_group_url)
                    <a href="{{ $kelompok->wa_group_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        <x-icon name="whatsapp" class="w-4 h-4 text-emerald-500" />
                        Kontak WA Grup
                    </a>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="students-table" class="w-full text-left text-sm border-collapse">
                <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-semibold">
                    <th class="py-3.5 px-6 text-center w-16">No</th>
                    <th class="py-3.5 px-6 w-44">NIM</th>
                    <th class="py-3.5 px-6">Nama Mahasiswa</th>
                    <th class="py-3.5 px-6 w-64">No Telepon</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse ($mahasiswas as $m)
                    <tr class="hover:bg-blue-50/40 transition-colors group {{ $loop->even ? 'bg-slate-50/30' : '' }}">
                        <td class="py-3.5 px-6 text-center font-medium text-slate-500 text-xs">{{ $loop->iteration }}</td>
                        <td class="py-3.5 px-6 font-mono font-semibold text-slate-900 text-xs tracking-tight">{{ $m->nim }}</td>
                        <td class="py-3.5 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $m->avatar_class }} font-bold text-xs flex items-center justify-center flex-shrink-0">{{ $m->inisial }}</div>
                                <span class="font-bold text-slate-900 group-hover:text-ums-blue transition-colors">{{ $m->nama }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-6">
                            @if ($m->wa_url)
                                <a href="{{ $m->wa_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-slate-700 hover:text-emerald-600 font-mono text-xs transition-colors">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    {{ $m->no_telp }}
                                </a>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-10 text-center text-slate-400 text-sm">Belum ada mahasiswa di kelompok ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-white border-t border-slate-200/80 text-xs text-slate-500 font-medium">
            Menampilkan <span id="shown-count" class="font-bold text-slate-800">{{ $total }}</span>
            dari <span class="font-bold text-slate-800">{{ $total }}</span> total mahasiswa tutorial
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('search-input');
    const rows = document.querySelectorAll('#students-table tbody tr');
    const shown = document.getElementById('shown-count');
    input.addEventListener('input', e => {
        const q = e.target.value.toLowerCase().trim();
        let n = 0;
        rows.forEach(r => {
            const hit = r.textContent.toLowerCase().includes(q);
            r.style.display = hit ? '' : 'none';
            if (hit) n++;
        });
        shown.textContent = n;
    });
</script>
@endpush
