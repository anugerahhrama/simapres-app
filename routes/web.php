<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\MinatController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\RekomendasiLombaController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\VerifLombaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranLombasController;
use App\Models\PendaftaranLombas;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    return view('landing.index');
});

Route::resource('spk', SpkController::class)->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::prefix('profile')->controller(ProfileController::class)->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::put('update/{id}', 'update')->name('update');
    Route::get('photo/{id}', 'editPhoto')->name('photo.edit');
    Route::put('photo/update/{id}', 'updatePhoto')->name('photo.update');

    // keahlian
    Route::prefix('keahlian')->name('keahlian.')->group(function () {
        Route::get('create', 'createKeahlian')->name('create');
        Route::post('store', 'storeKeahlian')->name('store');
        Route::delete('destroy/{id}', 'deleteKeahlian')->name('destroy');
    });

    // tingkatan
    Route::prefix('tingkatan')->name('tingkatan.')->group(function () {
        Route::get('create', 'tingkatanCreate')->name('create');
        Route::post('store', 'tingkatanStore')->name('store');
    });

    // jenis pendaftaran
    Route::prefix('jenis')->name('jenis.')->group(function () {
        Route::get('create', 'createJenis')->name('create');
        Route::post('store', 'storeJenis')->name('store');
        Route::delete('{id}', 'deleteJenis')->name('destroy');
    });

    // biaya pendaftaran
    Route::prefix('biaya')->name('biaya.')->group(function () {
        Route::get('create', 'createBiaya')->name('create');
        Route::post('store', 'storeBiaya')->name('store');
        Route::delete('{id}', 'deleteBiaya')->name('destroy');
    });

    // hadiah/benefit
    Route::prefix('hadiah')->name('hadiah.')->group(function () {
        Route::get('create', 'createHadiah')->name('create');
        Route::post('store', 'storeHadiah')->name('store');
        Route::delete('{id}', 'deleteHadiah')->name('destroy');
    });
});

Route::prefix('manajemen')->group(function () {

    // Levels
    Route::resource('levels', LevelController::class)->middleware(['auth', 'level:ADM']);
    Route::prefix('levels')->controller(LevelController::class)->name('levels.')->middleware(['auth', 'level:ADM'])->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Periode
    Route::resource('periodes', PeriodeController::class)->middleware(['auth', 'level:ADM']);
    Route::prefix('periodes')->controller(PeriodeController::class)->name('periodes.')->middleware(['auth', 'level:ADM'])->group(function () {
        Route::post('list',  'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Prodi
    Route::resource('prodis', ProgramStudiController::class)->middleware(['auth', 'level:ADM']);
    Route::prefix('prodis')->controller(ProgramStudiController::class)->name('prodis.')->middleware(['auth', 'level:ADM'])->group(function () {
        Route::post('list',  'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Detail Users
    Route::resource('detailusers', DetailUserController::class)->middleware(['auth', 'level:ADM']);
    Route::prefix('detailusers')->controller(DetailUserController::class)->name('detailusers.')->middleware(['auth', 'level:ADM'])->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
        Route::get('changepass/{id}', 'changePass')->name('pass');
        Route::put('changepass/{id}', 'changePassUpdate')->name('pass.update');
    });

    // Lomba
    Route::resource('lomba', LombaController::class)->middleware(['auth', 'level:ADM,DSN']);
    Route::prefix('lomba')->controller(LombaController::class)->name('lomba.')->middleware(['auth', 'level:ADM,DSN'])->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Bimbingan
    Route::resource('bimbingan', BimbinganController::class);
    Route::prefix('bimbingan')->controller(BimbinganController::class)->name('bimbingan.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
        Route::post('{id}/update-status', [BimbinganController::class, 'updateStatus'])->name('updateStatus');
    });
    
    // Pendaftar Lomba
    Route::resource('pendaftaranLomba', PendaftaranLombasController::class);
    Route::prefix('pendaftaranLomba')->controller(PendaftaranLombasController::class)->name('pendaftaranLomba.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });
});

Route::prefix('verifikasi')->middleware(['auth', 'level:ADM'])->group(function () {

    // Verifikasi Prestasi
    Route::prefix('verifPres')->controller(PrestasiController::class)->name('verifPres.')->group(function () {
        Route::get('/', 'index_verif')->name('index');
        Route::post('list', 'list_verif')->name('list');
        Route::get('{id}', 'show_verif')->name('show');
        Route::post('/{id}/update-status', [PrestasiController::class, 'updateStatus'])->name('updateStatus');
    });

    // Lomba
    Route::resource('verifLomba', VerifLombaController::class);
    Route::prefix('verifLomba')->controller(VerifLombaController::class)->name('verifLomba.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });
});

Route::get('rekomendasi-lomba', [RekomendasiLombaController::class, 'index'])->middleware(['auth', 'level:MHS'])->name('rekomendasi.lomba');

Route::prefix('manajemen')->middleware(['auth', 'level:MHS', 'check.profile.complete'])->group(function () {

    // rekomendasi lomba
    Route::get('rekomendasi-lomba', [RekomendasiLombaController::class, 'index'])->name('rekomendasi.lomba');
    Route::get('rekomendasi-list', [RekomendasiLombaController::class, 'list'])->name('rekomendasi.list');

    // pendaftaran lomba
    Route::get('pendaftaran-lomba/{slug}', [PendaftaranLombasController::class, 'create'])->name('pendaftaran');
    Route::post('pendaftaran-lomba', [PendaftaranLombasController::class, 'store'])->name('pendaftaran.store');

    // Prestasi
    Route::resource('prestasi', PrestasiController::class);
    Route::prefix('prestasi')->controller(PrestasiController::class)->name('prestasi.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    //Route untuk fitur monitoring
    Route::resource('monitoring', MonitoringController::class)->only(['index']);
    Route::get('monitoring/cetak', [MonitoringController::class, 'cetak'])->name('monitoring.cetak');
});


Route::resource('minats', MinatController::class);
Route::get('minats/list', [MinatController::class, 'list'])->name('minats.list');
Route::get('minats/{minat}/confirm', [MinatController::class, 'confirm'])->name('minats.confirm');

require __DIR__ . '/auth.php';
