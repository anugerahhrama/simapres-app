<?php

use App\Http\Controllers\LevelController;
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

Route::get('/', function () {
    return view('index');
})->middleware(['auth', 'level:ADM']);

Route::prefix('manajemen')->group(function () {
    Route::resource('levels', LevelController::class)->middleware(['auth', 'level:ADM']);
});

require __DIR__ . '/auth.php';
