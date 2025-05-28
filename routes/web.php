<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\PeriodeController;
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
})->name('dashboard');

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
});

// Prestasi - Resource Routes dengan nama yang sudah disesuaikan
Route::resource('prestasi', PrestasiController::class);
Route::prefix('prestasi')->controller(PrestasiController::class)->name('prestasi.')->group(function () {
    Route::post('list', 'list')->name('list');
    Route::get('confirm/{id}', 'confirm')->name('confirm');
});

require __DIR__ . '/auth.php';