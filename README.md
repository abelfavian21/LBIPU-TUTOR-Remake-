# Portal Tutor – Tutorial LPIDB UMS (Laravel)

Aplikasi web untuk tutor LPIDB/ETP UMS: daftar peserta, presensi harian, nilai harian, dan jurnal per pertemuan.
Tampilan mengikuti desain Stitch yang diberikan (Tailwind via CDN, tanpa build Node).

**Alur:** Login → langsung **Dashboard (Daftar Peserta)** → menu *Nilai & Presensi* / *Rekap Mahasiswa* / *Tutor & Jurnal* / *Data Diri* / *Update Password*.

## Persyaratan
PHP ≥ 8.2 (ekstensi: mbstring, xml, pdo_mysql / pdo_sqlite, curl, zip), Composer, MySQL/MariaDB (XAMPP/Laragon) atau SQLite.

## Instalasi
```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate

# MySQL: buat database kosong bernama  tutorial_lpidb  (atau ubah DB_DATABASE di .env)
php artisan migrate --seed
php artisan serve
```
Buka http://localhost:8000

**Akun contoh (dari seeder):** username `abel` (atau NIM `L200250035`) / password `password`
→ segera ganti lewat menu *Update Password*.

> Pakai SQLite? Di `.env` set `DB_CONNECTION=sqlite`, hapus baris `DB_HOST/PORT/DATABASE/USERNAME/PASSWORD`,
> lalu `touch database/database.sqlite` sebelum `php artisan migrate --seed`.

## Struktur database
| Tabel | Isi utama |
|---|---|
| `tutors` | **username**, **password** (bcrypt), **nim** (mis. L200250035), nama, no_telp, email, status |
| `semesters` | kode (20261), nama, tanggal mulai/selesai, `is_active` |
| `kelompoks` | tutor_id, semester_id, kode (L200N), kelas, prodi, wa_group_url |
| `mahasiswas` | kelompok_id, **nim**, **nama**, **no_telp**, email, prodi, angkatan, jenis_kelamin, status |
| `pertemuans` | kelompok_id, nomor (1–14), topik, **tanggal** — dibuat otomatis saat kelompok dibuat |
| `presensis` | pertemuan_id, mahasiswa_id, status (`hadir`/`izin`/`absen`) — **harian**, unik per mahasiswa+pertemuan |
| `nilais` | pertemuan_id, mahasiswa_id, nilai 1–5 — **harian**, unik per mahasiswa+pertemuan |
| `jurnals` | pertemuan_id (unik), tutor_id, tanggal, jumlah_hadir, materi, catatan — **harian** |

Relasi: `Tutor 1─n Kelompok 1─n Mahasiswa`, `Kelompok 1─n Pertemuan 1─n Presensi/Nilai (per mahasiswa)`, `Pertemuan 1─1 Jurnal`.

**Perhitungan otomatis per mahasiswa** (menu *Rekap Mahasiswa*, `Mahasiswa::withRekap()`):
jumlah hadir / izin / absen, % kehadiran (hadir ÷ pertemuan yang sudah dicatat presensinya),
jumlah nilai terisi, dan rata-rata nilai. Batas merah kehadiran = 75% (`config/lpidb.php`).

## Menambah tutor & mahasiswa
Belum ada panel admin; gunakan perintah artisan:
```bash
php artisan lpidb:tambah-tutor budi L200250099 "BUDI SANTOSO" L200P --password=rahasia123
php artisan lpidb:impor-mahasiswa L200P database/contoh-mahasiswa.csv   # kolom: nim,nama,no_telp,email
```

## Fitur per halaman
- **Login** – username atau NIM; rate-limit 10x/menit; tombol CAS UMS masih placeholder.
- **Dashboard / Peserta** – info tutor & kelompok, pencarian langsung, Export Excel (CSV), tautan WhatsApp mahasiswa.
- **Nilai & Presensi** – progres 14 pertemuan, tombol Isi/Ubah Presensi, Nilai, Jurnal, Cetak, Unduh rekap jurnal.
- **Presensi** – Hadir/Izin/Absen per mahasiswa, "Tandai Semua Hadir", tanggal pelaksanaan, bar kehadiran langsung.
- **Nilai** – input skala 1–5, nilai ≤ 2 ditandai merah, cetak form.
- **Jurnal** – tanggal, jumlah hadir (otomatis dari presensi), materi, catatan; halaman riwayat + unduh CSV.
- **Rekap Mahasiswa** – tabel rekap + halaman cetak matriks presensi & nilai (A4 landscape).
- **Data Diri** & **Update Password**.

## Pengaturan
`config/lpidb.php` : jumlah pertemuan, skala nilai, batas kehadiran, topik default tiap pertemuan.
Zona waktu `Asia/Jakarta` dan bahasa `id` diatur di `.env`.

## Catatan
- Tailwind & font dimuat dari CDN (butuh internet saat dibuka). Untuk produksi sebaiknya di-build dengan Vite.
- Setiap tutor hanya bisa membuka pertemuan miliknya sendiri (403 jika mencoba URL pertemuan tutor lain).
