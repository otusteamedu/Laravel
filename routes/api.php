<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v2\SearchController;
use App\Http\Controllers\Api\v3\ProfileController;
use Illuminate\Support\Facades\Route;

function v1Routes()
{
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
}

function v2Routes()
{
    v1Routes();
    Route::get('/search', SearchController::class);
}

Route::prefix('v1')->name('v1.')->middleware('auth:api')->group(function () {
    v1Routes();
});

Route::prefix('v2')->name('v2.')->middleware('auth:api')->group(function () {
    v2Routes();
});

Route::prefix('v3')->name('v3.')->middleware('auth:api')->group(function () {
    v2Routes();
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::get('/orders', [ProfileController::class, 'getOrders']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');