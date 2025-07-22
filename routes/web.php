<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\SettingController;

// Главная страница
Route::get('/', function () {
    return view('home', ['title' => 'ТСЖ Радуга']);
})->name('index');

// Страница тарифов
Route::get('/tariffs', function () {
    return view('tariffs', ['title' => 'Тарифы']);
})->name('tariffs.index');

// Страница квартир
Route::get('/apartments', function () {
    return view('apartments.index', [
        'title' => 'Квартиры',
        'apartments' => []
    ]);
})->name('apartments.index');

// Страница dashboard — редирект после логина Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Маршруты профиля (Breeze их ожидает)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Админская зона
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('settings', SettingController::class)->only([
        'index', 'edit', 'update'
    ]);
    Route::resource('apartments', ApartmentController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
});

// Auth-маршруты Breeze (login, register, logout, reset password и т.д.)
require __DIR__.'/auth.php';


Route::get('/fail', function () {
    throw new \Exception("Test error to Telegram");
});
