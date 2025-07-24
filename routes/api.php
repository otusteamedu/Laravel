<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NewsController as V1NewsController;
use App\Http\Controllers\Api\V2\NewsController as V2NewsController;
use App\Http\Controllers\Api\V1\AuthController as V1AuthController;
use App\Http\Controllers\Api\V2\AuthController as V2AuthController;

// JWT routes
Route::prefix('v1') ->as('v1.')->group(function () {
    Route::post('/login', [V1AuthController::class, 'login'])->name('login');
    Route::post('/refresh', [V1AuthController::class, 'refresh'])->name('refresh');
});

Route::middleware('auth:api_v1')
     ->prefix('v1')
     ->as('v1.')
     ->group(function () {
         Route::post('/logout', [V1AuthController::class, 'logout'])->name('logout');

         Route::apiResource('/news', V1NewsController::class);
     });


// OAuth routes

Route::prefix('v2') ->as('v2.')->group(function () {
    Route::post('/login', [V2AuthController::class, 'login']);
    Route::post('/register', [V2AuthController::class, 'register']);
});

Route::prefix('v2')
    ->as('v2.')
    ->middleware('auth:api_v2')
    ->group(function () {
        Route::get('/user', [V2AuthController::class, 'user']);
        Route::post('/logout', [V2AuthController::class, 'logout']);

        Route::apiResource('news', V2NewsController::class);
});
