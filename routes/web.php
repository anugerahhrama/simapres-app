<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\MinatController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\LombaController;
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

Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard')->middleware('auth');

Route::prefix('manajemen')->middleware(['auth', 'level:ADM'])->group(function () {

    // Levels
    Route::resource('levels', LevelController::class);
    Route::prefix('levels')->controller(LevelController::class)->name('levels.')->group(function () {
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
    });
});

// Prestasi - Resource Routes dengan nama yang sudah disesuaikan
Route::resource('prestasi', PrestasiController::class);
Route::prefix('prestasi')->controller(PrestasiController::class)->name('prestasi.')->group(function () {
    Route::post('list', 'list')->name('list');
    Route::get('confirm/{id}', 'confirm')->name('confirm');
});

Route::resource('minats', MinatController::class);
Route::get('minats/list', [MinatController::class, 'list'])->name('minats.list');
Route::get('minats/{minat}/confirm', [MinatController::class, 'confirm'])->name('minats.confirm');

Route::resource('lombas', LombaController::class);
Route::get('lombas/list', [LombaController::class, 'list'])->name('lombas.list');
Route::get('confirm/{lomba}', [LombaController::class, 'confirm'])->name('lombas.confirm');

require __DIR__ . '/auth.php';