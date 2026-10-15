@extends('layouts.app')
@section('title', 'Presensi Pertemuan '.$pertemuan->nomor)
@section('crumb_mid', 'Nilai & Presensi')
@section('crumb', 'Presensi Mahasiswa (Pertemuan ke-'.$pertemuan->nomor.')')

@section('content')
<form id="form-presensi" method="POST" action="{{ route('presensi.update', $pertemuan) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <section class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200/60 flex flex-col gap-6 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-blue-50/70 pointer-events-none blur-2xl"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Presensi Tatap Muka</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Presensi Mahasiswa</h1>
                <p class="text-sm text-slate-500 max-w-xl">Catat kehadiran mahasiswa pada setiap pertemuan tutorial. Presensi dihitung otomatis ke rekap.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="btn-all" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-sm font-semibold transition active:scale-95">
                    <x-icon name="check-all" class="w-4 h-4 text-emerald-600" /> Tandai Semua Hadir
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md shadow-blue-600/25 transition active:scale-95">
                    <x-icon name="check-circle" class="w-4 h-4" /> Simpan Presensi
                </button>
            </div>
        </div>

        {{-- Pilih pertemuan & tanggal --}}
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
            <div>
                <label for="pilih-pertemuan" class="block text-xs font-semibold text-slate-600 mb-1.5">Pertemuan ke</label>
                <select id="pilih-pertemuan" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($pertemuans as $p)
                        <option value="{{ route('presensi.edit', $p) }}" @selected($p->id === $pertemuan->id)>
                            Pertemuan ke-{{ $p->nomor }}{{ $p->topik ? ' - '.$p->topik : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tanggal" class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Pelaksanaan</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', ($pertemuan->tanggal ?? now())->format('Y-m-d')) }}"
                       class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('tanggal') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><x-icon name="clipboard-check" /></div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 leading-tight">Daftar Presensi Kelompok {{ $kelompok->kode }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Program Studi {{ $kelompok->prodi }} &bull; Semester {{ $kelompok->semester->nama }}</p>
                </div>
            </div>
            <div class="w-full md:w-64 flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-medium">Tingkat Kehadiran</span>
                    <span id="rate-text" class="font-bold text-emerald-600">0%</span>
                </div>
                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden flex">
                    <div id="bar-hadir" class="bg-emerald-500 h-full transition-all duration-300" style="width:0%"></div>
                    <div id="bar-izin" class="bg-amber-400 h-full transition-all duration-300" style="width:0%"></div>
                    <div id="bar-absen" class="bg-rose-500 h-full transition-all duration-300" style="width:0%"></div>
                </div>
                <div class="flex gap-3 text-[11px] text-slate-500">
                    <span>Hadir <b id="n-hadir" class="text-emerald-600">0</b></span>
                    <span>Izin <b id="n-izin" class="text-amber-600">0</b></span>
                    <span>Absen <b id="n-absen" class="text-rose-600">0</b></span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <p class="px-6 pb-3 text-xs text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                <tr class="bg-slate-50/75 text-slate-400 uppercase text-[11px] font-bold tracking-wider">
                    <th class="py-3.5 px-4 w-14 text-center">No</th>
                    <th class="py-3.5 px-4 w-36">NIM</th>
                    <th class="py-3.5 px-6">Nama Mahasiswa</th>
                    <th class="py-3.5 px-8 text-right min-w-[280px]">Status Kehadiran</th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100/80">
                @foreach ($mahasiswas as $m)
                    @php $status = old("presensi.{$m->id}", $presensi[$m->id] ?? 'hadir'); @endphp
                    <tr data-row class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-4 text-center font-bold text-slate-400 text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-4 px-4 font-mono font-medium text-slate-700 text-xs">{{ $m->nim }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $m->avatar_class }} font-bold text-xs flex items-center justify-center flex-shrink-0">{{ $m->inisial }}</div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $m->nama }}</p>
                                    <span class="text-[11px] text-slate-400">{{ $m->prodi }} &bull; {{ $kelompok->kelas }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-8 text-right">
                            <div class="inline-flex p-1 rounded-xl bg-slate-100/90 gap-1">
                                <label class="cursor-pointer">
                                    <input type="radio" class="peer sr-only" name="presensi[{{ $m->id }}]" value="hadir" @checked($status === 'hadir')>
                                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-400">
                                        <x-icon name="check" class="w-3.5 h-3.5" /> Hadir
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" class="peer sr-only" name="presensi[{{ $m->id }}]" value="izin" @checked($status === 'izin')>
                                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-amber-400">
                                        <x-icon name="clock" class="w-3.5 h-3.5" /> Izin
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" class="peer sr-only" name="presensi[{{ $m->id }}]" value="absen" @checked($status === 'absen')>
                                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-rose-400">
                                        <x-icon name="x" class="w-3.5 h-3.5" /> Absen
                                    </span>
                                </label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-slate-400">
                @if ($sudahDiisi) Presensi pertemuan ini sudah pernah disimpan. @else Belum disimpan &mdash; semua mahasiswa diset <b>Hadir</b> sebagai nilai awal. @endif
            </p>
            <div class="flex gap-2">
                <a href="{{ route('laporan') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm">
                    <x-icon name="save" class="w-4 h-4" /> Simpan Presensi
                </button>
            </div>
        </div>
    </section>
</form>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('form-presensi');
    const $ = id => document.getElementById(id);

    function recalc() {
        const rows = form.querySelectorAll('[data-row]');
        let h = 0, i = 0, a = 0;
        rows.forEach(r => {
            const v = r.querySelector('input[type=radio]:checked')?.value ?? 'hadir';
            if (v === 'hadir') h++; else if (v === 'izin') i++; else a++;
            r.classList.toggle('bg-rose-50/40', v === 'absen');
        });
        const n = rows.length || 1;
        $('bar-hadir').style.width = (h / n * 100) + '%';
        $('bar-izin').style.width = (i / n * 100) + '%';
        $('bar-absen').style.width = (a / n * 100) + '%';
        $('rate-text').textContent = Math.round(h / n * 100) + '%';
        $('n-hadir').textContent = h; $('n-izin').textContent = i; $('n-absen').textContent = a;
    }

    form.addEventListener('change', recalc);
    $('btn-all').addEventListener('click', () => {
        form.querySelectorAll('input[type=radio][value=hadir]').forEach(r => r.checked = true);
        recalc();
    });
    $('pilih-pertemuan').addEventListener('change', e => { window.location = e.target.value; });
    recalc();
</script>
@endpush
