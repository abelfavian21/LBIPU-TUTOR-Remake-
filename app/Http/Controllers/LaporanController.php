<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;

class LaporanController extends Controller
{
    use ResolvesKelompok;

    /** Menu "Nilai & Presensi": daftar 14 pertemuan + status isian. */
    public function index()
    {
        $kelompok = $this->kelompokAktif();
        if (! $kelompok) {
            return $this->kosong();
        }

        $pertemuans = $kelompok->pertemuans()
            ->withCount([
                'presensis',
                'nilais as nilai_terisi_count' => fn ($q) => $q->whereNotNull('nilai'),
            ])
            ->with('jurnal')
            ->get();

        $total = $pertemuans->count();
        $statistik = [
            'total' => $total,
            'presensi' => $pertemuans->where('presensis_count', '>', 0)->count(),
            'nilai' => $pertemuans->where('nilai_terisi_count', '>', 0)->count(),
            'jurnal' => $pertemuans->filter(fn ($p) => $p->jurnal)->count(),
        ];

        return view('laporan.index', compact('kelompok', 'pertemuans', 'statistik'));
    }
}
