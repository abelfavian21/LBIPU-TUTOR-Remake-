<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Data yang selalu dibutuhkan layout (sidebar, badge peserta, pill semester)
        View::composer('layouts.app', function ($view) {
            $tutor = auth()->user();
            $kelompok = $tutor?->kelompokAktif();

            $view->with([
                'tutor' => $tutor,
                'kelompokAktif' => $kelompok,
                'semesterAktif' => $kelompok?->semester,
                'jumlahPeserta' => $kelompok ? $kelompok->mahasiswas()->count() : 0,
            ]);
        });
    }
}
