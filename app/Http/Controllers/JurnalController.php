<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;
use App\Models\Jurnal;
use App\Models\Pertemuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    use ResolvesKelompok;

    /** Menu "Tutor & Jurnal": riwayat jurnal semua pertemuan. */
    public function index()
    {
        $kelompok = $this->kelompokAktif();
        if (! $kelompok) {
            return $this->kosong();
        }

        $pertemuans = $kelompok->pertemuans()->with('jurnal')->get();
        $totalMahasiswa = $kelompok->mahasiswas()->count();

        return view('jurnal.index', compact('kelompok', 'pertemuans', 'totalMahasiswa'));
    }

    public function edit(Pertemuan $pertemuan)
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);

        $jurnal = $pertemuan->jurnal;
        $totalMahasiswa = $kelompok->mahasiswas()->count();
        $hadirDariPresensi = $pertemuan->presensis()->where('status', 'hadir')->count();
        $pertemuans = $kelompok->pertemuans()->get();

        // Nilai awal formulir: pakai jurnal bila ada, kalau belum ambil dari presensi/pertemuan
        $form = [
            'tanggal' => ($jurnal?->tanggal ?? $pertemuan->tanggal ?? now())->format('Y-m-d'),
            'jumlah_hadir' => $jurnal?->jumlah_hadir ?? $hadirDariPresensi,
            'materi' => $jurnal?->materi ?? $pertemuan->topik,
            'catatan' => $jurnal?->catatan,
        ];

        return view('jurnal.form', compact('kelompok', 'pertemuan', 'pertemuans', 'jurnal', 'form', 'totalMahasiswa'));
    }

    public function update(Request $request, Pertemuan $pertemuan): RedirectResponse
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);
        $total = $kelompok->mahasiswas()->count();

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'jumlah_hadir' => ['required', 'integer', 'min:0', "max:$total"],
            'materi' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'jumlah_hadir.max' => "Jumlah hadir tidak boleh melebihi $total mahasiswa.",
        ]);

        Jurnal::updateOrCreate(
            ['pertemuan_id' => $pertemuan->id],
            $data + ['tutor_id' => auth()->id()]
        );
        $pertemuan->update(['tanggal' => $data['tanggal']]);

        return redirect()->route('jurnal.edit', $pertemuan)
            ->with('success', 'Jurnal pertemuan berhasil diperbarui & disimpan!');
    }

    public function export()
    {
        $kelompok = $this->kelompokAktif();
        abort_unless($kelompok, 404);

        $baris = $kelompok->pertemuans()->with('jurnal')->get()->map(fn ($p) => [
            $p->nomor,
            optional($p->jurnal?->tanggal ?? $p->tanggal)->format('d/m/Y'),
            $p->jurnal?->materi ?? '',
            $p->jurnal?->jumlah_hadir ?? '',
            $p->jurnal?->catatan ?? '',
        ]);

        return $this->csv(
            'rekap-jurnal-'.$kelompok->kode.'.csv',
            ['Pertemuan', 'Tanggal', 'Materi', 'Jumlah Hadir', 'Catatan'],
            $baris
        );
    }
}
