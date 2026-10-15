<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai &amp; Presensi {{ $kelompok->kode }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #111; margin: 24px; }
        h1 { font-size: 16px; margin: 0 0 2px; } h2 { font-size: 13px; margin: 22px 0 6px; }
        .meta { color: #444; margin-bottom: 8px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        th, td { border: 1px solid #999; padding: 3px 4px; text-align: center; }
        th { background: #e8eef5; } td.l, th.l { text-align: left; }
        .H { color: #047857; font-weight: bold; } .I { color: #b45309; font-weight: bold; } .A { color: #be123c; font-weight: bold; }
        .legend { color: #555; font-size: 10px; }
        .noprint { margin-bottom: 14px; }
        button { padding: 6px 14px; cursor: pointer; }
        @media print { .noprint { display: none; } body { margin: 8mm; } @page { size: A4 landscape; } }
    </style>
</head>
<body>
<div class="noprint"><button onclick="window.print()">Cetak / Simpan PDF</button></div>

<h1>Rekap Nilai &amp; Presensi Tutorial LPIDB UMS</h1>
<div class="meta">
    Tutor: <b>{{ auth()->user()->nama }}</b> ({{ auth()->user()->nim }}) &nbsp;|&nbsp;
    Kelompok: <b>{{ $kelompok->kode }}</b> ({{ $kelompok->kelas }}) &nbsp;|&nbsp;
    Semester: <b>{{ $kelompok->semester->nama }}</b><br>
    Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
</div>

<h2>A. Presensi per Pertemuan</h2>
<table>
    <thead>
    <tr>
        <th>No</th><th class="l">NIM</th><th class="l">Nama</th>
        @foreach ($pertemuans as $p)<th>{{ $p->nomor }}</th>@endforeach
        <th>H</th><th>I</th><th>A</th><th>%</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($mahasiswas as $m)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="l">{{ $m->nim }}</td>
            <td class="l">{{ $m->nama }}</td>
            @foreach ($pertemuans as $p)
                @php $s = $presensi[$m->id][$p->id]->status ?? null; @endphp
                <td>
                    @if ($s === 'hadir') <span class="H">H</span>
                    @elseif ($s === 'izin') <span class="I">I</span>
                    @elseif ($s === 'absen') <span class="A">A</span>
                    @else - @endif
                </td>
            @endforeach
            <td>{{ $m->hadir_count }}</td><td>{{ $m->izin_count }}</td><td>{{ $m->absen_count }}</td>
            <td>{{ $m->persen_hadir !== null ? $m->persen_hadir.'%' : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="legend">H = Hadir, I = Izin, A = Absen, - = belum diisi. % = hadir &divide; pertemuan yang sudah dicatat ({{ $terlaksana }} pertemuan).</div>

<h2>B. Nilai per Pertemuan (skala {{ config('lpidb.nilai_min') }}-{{ config('lpidb.nilai_max') }})</h2>
<table>
    <thead>
    <tr>
        <th>No</th><th class="l">NIM</th><th class="l">Nama</th>
        @foreach ($pertemuans as $p)<th>{{ $p->nomor }}</th>@endforeach
        <th>Rata-rata</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($mahasiswas as $m)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="l">{{ $m->nim }}</td>
            <td class="l">{{ $m->nama }}</td>
            @foreach ($pertemuans as $p)
                <td>{{ $nilai[$m->id][$p->id]->nilai ?? '-' }}</td>
            @endforeach
            <td><b>{{ $m->rata_nilai !== null ? number_format($m->rata_nilai, 2, ',', '') : '-' }}</b></td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
