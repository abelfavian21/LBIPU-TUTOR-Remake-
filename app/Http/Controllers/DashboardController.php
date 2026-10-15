<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;

class DashboardController extends Controller
{
    use ResolvesKelompok;

    public function index()
    {
        $kelompok = $this->kelompokAktif();
        if (! $kelompok) {
            return $this->kosong();
        }

        $mahasiswas = $kelompok->mahasiswas()->orderBy('nim')->get();

        return view('dashboard', [
            'tutor' => auth()->user(),
            'kelompok' => $kelompok,
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function export()
    {
        $kelompok = $this->kelompokAktif();
        abort_unless($kelompok, 404);

        $baris = $kelompok->mahasiswas()->orderBy('nim')->get()
            ->values()
            ->map(fn ($m, $i) => [$i + 1, $m->nim, $m->nama, $m->no_telp, $m->email, $m->status]);

        return $this->csv(
            'peserta-'.$kelompok->kode.'.csv',
            ['No', 'NIM', 'Nama Mahasiswa', 'No Telepon', 'Email', 'Status'],
            $baris
        );
    }
}