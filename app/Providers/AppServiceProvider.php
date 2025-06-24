<?php

namespace App\Providers;

use App\Models\Prestasi;
use App\Models\Lomba;
use App\Models\PendaftaranLombas;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $jumlahVerifPrestasi = Prestasi::where('status_verifikasi', 'pending')->count();
            $jumlahVerifLomba = Lomba::where('status_verifikasi', 'pending')->count();
            $jumlahDaftar = PendaftaranLombas::where('status', 'pending')->count();
            
            $view->with([
                'jumlahVerifPrestasi' => $jumlahVerifPrestasi,
                'jumlahVerifLomba' => $jumlahVerifLomba,
                'jumlahDaftar' => $jumlahDaftar,
            ]);
        });
    }
}
