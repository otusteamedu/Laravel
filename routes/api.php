<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v2\SearchController;
use Illuminate\Support\Facades\Route;

function v1Routes()
{
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
}

Route::prefix('v1')->name('v1.')->middleware('auth:api')->group(function () {
    v1Routes();
});

Route::prefix('v2')->name('v2.')->middleware('auth:api')->group(function () {
    v1Routes();
    Route::get('/search', SearchController::class);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');