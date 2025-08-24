<?php

use App\Interfaces\Http\Controllers\Api\v1\AreaController as AreaController_v1;
use App\Interfaces\Http\Controllers\Api\v1\AuthController as AuthController_v1;
use App\Interfaces\Http\Controllers\Api\v2\AreaController as AreaController_v2;
use App\Interfaces\Http\Controllers\Api\v2\AuthController as AuthController_v2;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->name('v1.')->group(function () {
    Route::post('/register', [AuthController_v1::class, 'register'])->name('register');
    Route::post('/login', [AuthController_v1::class, 'login'])->name('login');

    Route::middleware('auth:api-v1')->name('api.')->group(function () {
        Route::apiResource('area', AreaController_v1::class);
    });
});


Route::prefix('/v2')->name('v2.')->group(function () {
    Route::post('/oauth/token', [\Laravel\Passport\Http\Controllers\AccessTokenController::class, 'issueToken'])->name('oauth.token');
    Route::post('/register', [AuthController_v2::class, 'register'])->name('register');
    Route::post('/login', [AuthController_v2::class, 'login'])->name('login');

    Route::middleware('auth:api-v2')->name('api.')->group(function () {
        Route::apiResource('area', AreaController_v2::class);
    });
});
