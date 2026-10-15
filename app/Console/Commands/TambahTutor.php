<?php

namespace App\Console\Commands;

use App\Models\Kelompok;
use App\Models\Semester;
use App\Models\Tutor;
use Illuminate\Console\Command;

class TambahTutor extends Command
{
    protected $signature = 'lpidb:tambah-tutor
                            {username : Username login tutor}
                            {nim : NIM tutor, contoh L200250035}
                            {nama : Nama lengkap tutor (pakai tanda kutip)}
                            {kelompok : Kode kelompok tutorial, contoh L200N}
                            {--password= : Password (kosongkan untuk diminta interaktif)}
                            {--telp= : Nomor telepon tutor}';

    protected $description = 'Tambah tutor baru beserta kelompok tutorialnya pada semester aktif (14 pertemuan dibuat otomatis).';

    public function handle(): int
    {
        $semester = Semester::aktif();
        if (! $semester) {
            $this->error('Belum ada semester aktif. Jalankan: php artisan db:seed atau buat semester dengan is_active = 1.');

            return self::FAILURE;
        }

        $password = $this->option('password') ?: $this->secret('Password untuk tutor ini');
        if (strlen((string) $password) < 6) {
            $this->error('Password minimal 6 karakter.');

            return self::FAILURE;
        }

        $tutor = Tutor::updateOrCreate(['username' => $this->argument('username')], [
            'password' => $password,
            'nim' => strtoupper($this->argument('nim')),
            'nama' => strtoupper($this->argument('nama')),
            'no_telp' => $this->option('telp'),
            'status' => 'aktif',
        ]);

        $kelompok = Kelompok::firstOrCreate(
            ['semester_id' => $semester->id, 'kode' => strtoupper($this->argument('kelompok'))],
            ['tutor_id' => $tutor->id]
        );

        $this->info("Tutor {$tutor->nama} ({$tutor->nim}) siap. Kelompok {$kelompok->kode} - semester {$semester->nama}.");
        $this->line("Selanjutnya impor mahasiswa: php artisan lpidb:impor-mahasiswa {$kelompok->kode} mahasiswa.csv");

        return self::SUCCESS;
    }
}
