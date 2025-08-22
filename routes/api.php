<?php

use App\Interfaces\Http\Controllers\Api\AreaController;
use App\Interfaces\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('area', AreaController::class);
});
