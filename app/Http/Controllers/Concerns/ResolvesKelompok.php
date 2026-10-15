<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Kelompok;
use App\Models\Pertemuan;

trait ResolvesKelompok
{
    /** Kelompok tutorial tutor yang sedang login pada semester aktif (bisa null). */
    protected function kelompokAktif(): ?Kelompok
    {
        return auth()->user()->kelompokAktif();
    }

    /** Pastikan pertemuan ini milik tutor yang sedang login. */
    protected function kelompokDariPertemuan(Pertemuan $pertemuan): Kelompok
    {
        $kelompok = $pertemuan->kelompok;
        abort_unless($kelompok && $kelompok->tutor_id === auth()->id(), 403, 'Pertemuan ini bukan milik Anda.');

        return $kelompok;
    }

    protected function kosong()
    {
        return response()->view('kosong');
    }

    /** Stream CSV yang langsung terbaca Excel (UTF-8 BOM, pemisah koma). */
    protected function csv(string $namaFile, array $header, iterable $baris)
    {
        return response()->streamDownload(function () use ($header, $baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $header);
            foreach ($baris as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
