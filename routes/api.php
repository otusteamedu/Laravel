<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v2\SearchController;
use App\Http\Controllers\Api\v3\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware('auth:api')->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
});

Route::prefix('v2')->name('v2.')->middleware('auth:api')->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::get('/search', SearchController::class);
});

Route::prefix('v3')->name('v3.')->middleware('auth:api')->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::get('/search', SearchController::class);
    Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile.get');
    Route::get('/profile/orders', [ProfileController::class, 'getOrders'])->name('profile.orders');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('api.logout');