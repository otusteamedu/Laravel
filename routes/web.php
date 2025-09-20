<?php

use App\Interfaces\Http\Controllers\AreaController;
use App\Interfaces\Http\Controllers\FibonachiController;
use App\Interfaces\Http\Controllers\HomeController;
use App\Interfaces\Http\Controllers\MeasureController;
use App\Interfaces\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::post('/get-recipe', [HomeController::class, 'getRecipe'])->name('home.getRecipe');
Route::get('/get-measure-by-product/{id}', [HomeController::class, 'getMeasureByProduct'])->name('home.getMeasureByProduct');

Route::middleware(['auth'])->group(function () {

    Route::resource('mesaure', MeasureController::class);
    Route::resource('area', AreaController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('fibonachi')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/',  [FibonachiController::class, 'index'])->name('fibonachi.index');
        Route::get('/calculate/{number}',  [FibonachiController::class, 'calculate'])
            ->name('fibonachi.calculate');
    });



/**
 * Для проверки работоспособности функционала:
 * для логирования ошибок
 * для кеширования сессии
 */

Route::get('/log', function () {
    Log::error('Test error for Telegram');
    dd('Test error for Telegram');
});

Route::prefix('/session')->group(function () {
    Route::get('/set', function () {
        session(['key' => 'value']);
        return 'Session set';
    });
    Route::get('/get', function () {
        return session('key');
    });
    Route::get('/id', function () {
        return 'Session ID: ' . session()->getId();
    });
});

Route::prefix('/cache')->group(function () {
    Route::get('/get', function () {
        return Cache::get('area.getAll');
    });
});
