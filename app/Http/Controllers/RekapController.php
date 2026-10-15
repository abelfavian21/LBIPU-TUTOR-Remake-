<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;
use App\Models\Kelompok;
use App\Models\Nilai;
use App\Models\Presensi;

class RekapController extends Controller
{
    use ResolvesKelompok;

    /** Rekap per mahasiswa: jumlah masuk (hadir/izin/absen), % kehadiran, rata-rata nilai. */
    private function rekap(Kelompok $kelompok): array
    {
        $pertemuans = $kelompok->pertemuans()->get();
        $ids = $pertemuans->pluck('id');

        // Pertemuan yang sudah dilaksanakan = sudah ada presensi tersimpan
        $terlaksana = Presensi::whereIn('pertemuan_id', $ids)->distinct()->count('pertemuan_id');

        $mahasiswas = $kelompok->mahasiswas()->withRekap($ids)->orderBy('nim')->get()
            ->each(function ($m) use ($terlaksana) {
                $m->persen_hadir = $terlaksana > 0 ? round($m->hadir_count / $terlaksana * 100) : null;
            });

        return compact('pertemuans', 'ids', 'terlaksana', 'mahasiswas');
    }

    public function index()
    {
        $kelompok = $this->kelompokAktif();
        if (! $kelompok) {
            return $this->kosong();
        }

        $data = $this->rekap($kelompok);
        $data['rataHadir'] = $data['mahasiswas']->whereNotNull('persen_hadir')->avg('persen_hadir');
        $data['rataNilai'] = $data['mahasiswas']->whereNotNull('rata_nilai')->avg('rata_nilai');
        $data['batas'] = (int) config('lpidb.batas_kehadiran', 75);

        return view('rekap.index', $data + compact('kelompok'));
    }

    public function export()
    {
        $kelompok = $this->kelompokAktif();
        abort_unless($kelompok, 404);

        $data = $this->rekap($kelompok);
        $baris = $data['mahasiswas']->values()->map(fn ($m, $i) => [
            $i + 1, $m->nim, $m->nama, $m->hadir_count, $m->izin_count, $m->absen_count,
            $m->persen_hadir !== null ? $m->persen_hadir.'%' : '-',
            $m->nilai_count,
            $m->rata_nilai !== null ? number_format($m->rata_nilai, 2, ',', '') : '-',
        ]);

        return $this->csv(
            'rekap-nilai-presensi-'.$kelompok->kode.'.csv',
            ['No', 'NIM', 'Nama', 'Hadir', 'Izin', 'Absen', '% Kehadiran', 'Jumlah Nilai', 'Rata-rata Nilai'],
            $baris
        );
    }

    /** Halaman cetak: matriks presensi & nilai per pertemuan. */
    public function cetak()
    {
        $kelompok = $this->kelompokAktif();
        abort_unless($kelompok, 404);

        $data = $this->rekap($kelompok);
        $presensi = Presensi::whereIn('pertemuan_id', $data['ids'])->get()
            ->groupBy('mahasiswa_id')->map(fn ($g) => $g->keyBy('pertemuan_id'));
        $nilai = Nilai::whereIn('pertemuan_id', $data['ids'])->get()
            ->groupBy('mahasiswa_id')->map(fn ($g) => $g->keyBy('pertemuan_id'));

        return view('rekap.cetak', $data + compact('kelompok', 'presensi', 'nilai'));
    }
}
