<?php

use App\Http\Controllers\Api\ApartmentChargeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\ApartmentDetailController;



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('apartment-charges', ApartmentChargeController::class);

    Route::prefix('v1')->group(function () {
        Route::apiResource('apartment-details', ApartmentDetailController::class);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
