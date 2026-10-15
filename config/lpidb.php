<?php

return [
    // Jumlah pertemuan tutorial per semester (dibuat otomatis saat kelompok dibuat)
    'jumlah_pertemuan' => 14,

    // Skala nilai harian
    'nilai_min' => 1,
    'nilai_max' => 5,

    // Batas minimal persentase kehadiran (rekap menandai merah bila di bawah ini)
    'batas_kehadiran' => 75,

    // Topik awal tiap pertemuan (bisa diubah per kelompok lewat database)
    'topik_default' => [
        'Kontrak Belajar & Pengantar Materi',
        'Konsep Dasar & Praktik Mandiri',
        'Pembahasan Latihan Soal Bab 1',
        'Implementasi Studi Kasus Terbimbing',
        'Evaluasi Tengah Tahap 1',
        'Review Proyek Kelompok Kecil',
        'Persiapan Ujian Tengah Semester (UTS)',
        'Pengenalan Materi Lanjutan Bab 2',
        'Praktik Integrasi Modul Terapan',
        'Bedah Kasus Algoritma Kompleks',
        'Eksplorasi Framework & Debugging',
        'Presentasi & Pengujian Proyek Akhir',
        'Finalisasi Nilai Tugas & Portofolio',
        'Refleksi Akhir & Penutupan Tutorial',
    ],
];
