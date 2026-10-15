<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// --- Belum login ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    Route::get('/login/cas', [AuthController::class, 'cas'])->name('login.cas');
});

// --- Sudah login (tutor) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard = Daftar Peserta
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/peserta/export', [DashboardController::class, 'export'])->name('peserta.export');

    // Nilai & Presensi
    Route::get('/nilai-presensi', [LaporanController::class, 'index'])->name('laporan');

    Route::get('/pertemuan/{pertemuan}/presensi', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::put('/pertemuan/{pertemuan}/presensi', [PresensiController::class, 'update'])->name('presensi.update');

    Route::get('/pertemuan/{pertemuan}/nilai', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('/pertemuan/{pertemuan}/nilai', [NilaiController::class, 'update'])->name('nilai.update');

    // Rekap per mahasiswa
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
    Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');
    Route::get('/rekap/cetak', [RekapController::class, 'cetak'])->name('rekap.cetak');

    // Tutor & Jurnal
    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/export', [JurnalController::class, 'export'])->name('jurnal.export');
    Route::get('/pertemuan/{pertemuan}/jurnal', [JurnalController::class, 'edit'])->name('jurnal.edit');
    Route::put('/pertemuan/{pertemuan}/jurnal', [JurnalController::class, 'update'])->name('jurnal.update');

    // Akun tutor
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
