<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\FibonachiController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('layouts.main');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('area', AreaController::class);

Route::middleware(['auth'])->group(function () {

    Route::resource('mesaure', MeasureController::class);

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

Route::get('/log', function() {
    Log::error('Test error for Telegram');
    dd('Test error for Telegram');
});

Route::prefix('/session')->group(function() {
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

Route::prefix('/cache')->group(function() {
    Route::get('/get', function () {
        return Cache::get('area.getAll');
    });
});
