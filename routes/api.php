<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
});

Route::post('/v1/login', LoginController::class)->name('v1.login');
