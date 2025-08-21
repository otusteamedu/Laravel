<?php

use App\Http\Controllers\Api\V1\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Protected routes (require JWT token)
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('/news', NewsController::class);
});
