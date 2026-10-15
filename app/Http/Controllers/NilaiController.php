<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;
use App\Models\Nilai;
use App\Models\Pertemuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    use ResolvesKelompok;

    public function edit(Pertemuan $pertemuan)
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);

        $mahasiswas = $kelompok->mahasiswas()->orderBy('nim')->get();
        $nilaiRows = $pertemuan->nilais()->get();
        $nilai = $nilaiRows->pluck('nilai', 'mahasiswa_id');
        $terakhirDisimpan = $nilaiRows->max('updated_at');
        $pertemuans = $kelompok->pertemuans()->get();

        return view('nilai.form', compact('kelompok', 'pertemuan', 'pertemuans', 'mahasiswas', 'nilai', 'terakhirDisimpan'));
    }

    public function update(Request $request, Pertemuan $pertemuan): RedirectResponse
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);

        $min = (int) config('lpidb.nilai_min', 1);
        $max = (int) config('lpidb.nilai_max', 5);

        $data = $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*' => ['nullable', 'integer', "between:$min,$max"],
        ], [
            'nilai.*.between' => "Nilai harus antara $min sampai $max.",
            'nilai.*.integer' => 'Nilai harus berupa angka bulat.',
        ]);

        $idMahasiswa = $kelompok->mahasiswas()->pluck('id');

        DB::transaction(function () use ($data, $pertemuan, $idMahasiswa) {
            foreach ($idMahasiswa as $id) {
                $nilai = $data['nilai'][$id] ?? null;
                $baris = Nilai::where('pertemuan_id', $pertemuan->id)->where('mahasiswa_id', $id)->first();

                if ($nilai === null) {
                    $baris?->update(['nilai' => null]);   // dikosongkan
                    continue;
                }
                Nilai::updateOrCreate(
                    ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $id],
                    ['nilai' => (int) $nilai]
                );
            }
        });

        return redirect()->route('nilai.edit', $pertemuan)
            ->with('success', "Nilai pertemuan ke-{$pertemuan->nomor} berhasil disimpan.");
    }
}
