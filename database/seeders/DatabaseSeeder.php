<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Semester aktif
        $semester = Semester::updateOrCreate(['kode' => '20261'], [
            'nama' => 'Ganjil 2026/2027',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2027-01-31',
            'is_active' => true,
        ]);

        // 2. Tutor (login: username "abel" atau NIM "L200250035", password: "password")
        $tutor = Tutor::updateOrCreate(['username' => 'abel'], [
            'password' => 'password',                 // otomatis di-hash (cast "hashed")
            'nim' => 'L200250035',
            'nama' => 'MUHAMMAD ABEL FAVIAN FADEY',
            'no_telp' => '081234567890',
            'email' => 'abel@student.ums.ac.id',
            'status' => 'aktif',
        ]);

        // 3. Kelompok tutorial (14 pertemuan dibuat otomatis oleh model Kelompok)
        $kelompok = Kelompok::firstOrCreate(
            ['semester_id' => $semester->id, 'kode' => 'L200N'],
            ['tutor_id' => $tutor->id, 'kelas' => 'Reguler', 'prodi' => 'Informatika']
        );

        // 4. Mahasiswa
        $data = [
            ['L200260160', 'PUTRI HAKIM', '082345689087'],
            ['L200260161', 'ALDHO RAMADHAN', '085126621223'],
            ['L200260162', 'BUDI ABADI JAYA', '0895363032456'],
            ['L200260163', 'BIMA SAKTI MAULANA', '081271792224'],
            ['L200260164', 'RIDHO ANGKASA', '083222164890'],
            ['L200260167', 'NIDAL KAISAR', '085345288647'],
            ['L200260168', 'PUTRA PANGERAN RATU', '081577362663'],
            ['L200260170', 'DEWA FAVIAN AHMAD', '085822310145'],
            ['L200260171', 'ALYA SAPUTRI', '085259908676'],
            ['L200260173', 'TAMA PUTRA AKBAR', '089513743988'],
        ];

        $mahasiswas = [];
        foreach ($data as [$nim, $nama, $telp]) {
            $mahasiswas[$nim] = Mahasiswa::updateOrCreate(['nim' => $nim], [
                'kelompok_id' => $kelompok->id,
                'nama' => $nama,
                'no_telp' => $telp,
                'prodi' => 'Informatika',
                'angkatan' => '2026',
                'status' => 'aktif',
            ]);
        }

        // 5. Contoh data pertemuan ke-1 (presensi, nilai, jurnal) agar dashboard tidak kosong
        $p1 = $kelompok->pertemuans()->where('nomor', 1)->first();
        $p1->update(['tanggal' => '2026-09-19']);

        $status = ['L200260163' => 'absen', 'L200260170' => 'izin'];
        $nilai = [
            'L200260160' => 5, 'L200260161' => 4, 'L200260162' => 4, 'L200260163' => 2, 'L200260164' => 5,
            'L200260167' => 4, 'L200260168' => 3, 'L200260170' => 4, 'L200260171' => 5, 'L200260173' => 4,
        ];

        foreach ($mahasiswas as $nim => $m) {
            Presensi::updateOrCreate(
                ['pertemuan_id' => $p1->id, 'mahasiswa_id' => $m->id],
                ['status' => $status[$nim] ?? 'hadir']
            );
            Nilai::updateOrCreate(
                ['pertemuan_id' => $p1->id, 'mahasiswa_id' => $m->id],
                ['nilai' => $nilai[$nim]]
            );
        }

        Jurnal::updateOrCreate(['pertemuan_id' => $p1->id], [
            'tutor_id' => $tutor->id,
            'tanggal' => '2026-09-19',
            'jumlah_hadir' => 8,
            'materi' => 'Self Introduction & Basic Conversational Skills',
            'catatan' => 'Mahasiswa aktif memperkenalkan diri dengan pronunciation yang baik. Sesi tanya jawab berjalan kondusif tanpa kendala fasilitas.',
        ]);
    }
}
