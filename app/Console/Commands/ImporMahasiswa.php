<?php

namespace App\Console\Commands;

use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Console\Command;

class ImporMahasiswa extends Command
{
    protected $signature = 'lpidb:impor-mahasiswa
                            {kelompok : Kode kelompok tutorial, contoh L200N}
                            {file : Path file CSV (kolom: nim,nama,no_telp,email)}';

    protected $description = 'Impor mahasiswa dari file CSV ke sebuah kelompok pada semester aktif.';

    public function handle(): int
    {
        $semester = Semester::aktif();
        $kelompok = $semester
            ? Kelompok::where('semester_id', $semester->id)->where('kode', strtoupper($this->argument('kelompok')))->first()
            : null;

        if (! $kelompok) {
            $this->error('Kelompok tidak ditemukan pada semester aktif. Buat dulu dengan lpidb:tambah-tutor.');

            return self::FAILURE;
        }

        $path = $this->argument('file');
        if (! is_file($path) || ($fh = fopen($path, 'r')) === false) {
            $this->error("File tidak bisa dibuka: $path");

            return self::FAILURE;
        }

        $header = fgetcsv($fh);
        if (! $header) {
            $this->error('File CSV kosong.');

            return self::FAILURE;
        }
        $header = array_map(fn ($h) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $h))), $header);

        if (! in_array('nim', $header, true) || ! in_array('nama', $header, true)) {
            $this->error('Header CSV wajib memuat kolom: nim, nama (opsional: no_telp, email).');

            return self::FAILURE;
        }

        $jumlah = 0;
        while (($row = fgetcsv($fh)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }
            $r = array_combine($header, array_map('trim', $row));
            if (($r['nim'] ?? '') === '' || ($r['nama'] ?? '') === '') {
                continue;
            }

            Mahasiswa::updateOrCreate(['nim' => strtoupper($r['nim'])], [
                'kelompok_id' => $kelompok->id,
                'nama' => strtoupper($r['nama']),
                'no_telp' => $r['no_telp'] ?? null,
                'email' => $r['email'] ?? null,
                'status' => 'aktif',
            ]);
            $jumlah++;
        }
        fclose($fh);

        $this->info("$jumlah mahasiswa diimpor ke kelompok {$kelompok->kode}.");

        return self::SUCCESS;
    }
}
