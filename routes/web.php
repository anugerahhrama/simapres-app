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

Route::get('spk', [SpkController::class, 'index'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard')->middleware('auth');

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
});

Route::prefix('manajemen')->middleware(['auth', 'level:ADM'])->group(function () {

    // Levels
    Route::resource('levels', LevelController::class);
    Route::prefix('levels')->controller(LevelController::class)->name('levels.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Periode
    Route::resource('periodes', PeriodeController::class);
    Route::prefix('periodes')->controller(PeriodeController::class)->name('periodes.')->group(function () {
        Route::post('list',  'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Prodi
    Route::resource('prodis', ProgramStudiController::class);
    Route::prefix('prodis')->controller(ProgramStudiController::class)->name('prodis.')->group(function () {
        Route::post('list',  'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });

    // Detail Users
    Route::resource('detailusers', DetailUserController::class);
    Route::prefix('detailusers')->controller(DetailUserController::class)->name('detailusers.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
        Route::get('changepass/{id}', 'changePass')->name('pass');
        Route::put('changepass/{id}', 'changePassUpdate')->name('pass.update');
    });

    // Lomba
    Route::resource('lomba', LombaController::class);
    Route::prefix('lomba')->controller(LombaController::class)->name('lomba.')->group(function () {
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
    Route::resource('verifLombas', LombaController::class);
    Route::prefix('verifLombas')->controller(LombaController::class)->name('verifLombas.')->group(function () {
        Route::post('list', 'list')->name('list');
        Route::get('confirm/{id}', 'confirm')->name('confirm');
    });
});

Route::get('rekomendasi-lomba', [RekomendasiLombaController::class, 'index'])->middleware(['auth', 'level:MHS'])->name('rekomendasi.lomba');

Route::prefix('manajemen')->middleware(['auth', 'level:MHS', 'check.profile.complete'])->group(function () {

    // rekomendasi lomba
    Route::get('rekomendasi-lomba', [RekomendasiLombaController::class, 'index'])->name('rekomendasi.lomba');
    Route::get('rekomendasi-list', [RekomendasiLombaController::class, 'list'])->name('rekomendasi.list');

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


// Bimbingan
Route::resource('bimbingan', BimbinganController::class);
Route::prefix('bimbingan')->controller(BimbinganController::class)->name('bimbingan.')->group(function () {
    Route::post('list', 'list')->name('list');
    Route::get('confirm/{id}', 'confirm')->name('confirm');
});

Route::resource('lomba', LombaController::class)->middleware(['auth', 'level:DSN']);
Route::prefix('lomba')->controller(LombaController::class)->name('lomba.')->group(function () {
    Route::post('list', 'list')->name('list');
    Route::get('confirm/{id}', 'confirm')->name('confirm');
});
require __DIR__ . '/auth.php';
