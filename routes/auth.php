<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VKController;
use App\Http\Controllers\Auth\YandexController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/login/yandex', [YandexController::class, 'redirect'])->name('login.yandex');
    Route::get('/login/yandex/callback', [YandexController::class, 'callback']);

    Route::get('/login/vk', [VKController::class, 'redirect'])->name('login.vk');
    Route::get('/login/vk/callback', [VKController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::any('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
