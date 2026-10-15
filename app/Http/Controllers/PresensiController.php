<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesKelompok;
use App\Models\Pertemuan;
use App\Models\Presensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PresensiController extends Controller
{
    use ResolvesKelompok;

    public function edit(Pertemuan $pertemuan)
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);

        $mahasiswas = $kelompok->mahasiswas()->orderBy('nim')->get();
        $presensi = $pertemuan->presensis()->pluck('status', 'mahasiswa_id');
        $pertemuans = $kelompok->pertemuans()->get();
        $sudahDiisi = $presensi->isNotEmpty();

        return view('presensi.form', compact('kelompok', 'pertemuan', 'pertemuans', 'mahasiswas', 'presensi', 'sudahDiisi'));
    }

    public function update(Request $request, Pertemuan $pertemuan): RedirectResponse
    {
        $kelompok = $this->kelompokDariPertemuan($pertemuan);

        $data = $request->validate([
            'tanggal' => ['nullable', 'date'],
            'presensi' => ['required', 'array'],
            'presensi.*' => ['required', Rule::in(Presensi::STATUS)],
        ]);

        $idMahasiswa = $kelompok->mahasiswas()->pluck('id');

        DB::transaction(function () use ($data, $pertemuan, $idMahasiswa) {
            if (! empty($data['tanggal'])) {
                $pertemuan->update(['tanggal' => $data['tanggal']]);
            }
            foreach ($idMahasiswa as $id) {
                $status = $data['presensi'][$id] ?? null;
                if (! $status) {
                    continue;
                }
                Presensi::updateOrCreate(
                    ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $id],
                    ['status' => $status]
                );
            }
        });

        return redirect()->route('presensi.edit', $pertemuan)
            ->with('success', "Presensi pertemuan ke-{$pertemuan->nomor} berhasil disimpan.");
    }
}
