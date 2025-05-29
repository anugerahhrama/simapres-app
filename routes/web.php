<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\DetailUserController;
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
})->name('dashboard')->middleware(['auth', 'level:ADM']);

Route::prefix('manajemen')->group(function () {

    // Levels
    Route::resource('levels', LevelController::class)->middleware(['auth', 'level:ADM']);
    Route::prefix('levels')->controller(LevelController::class)->name('levels.')->middleware(['auth', 'level:ADM'])->group(function () {
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

require __DIR__ . '/auth.php';