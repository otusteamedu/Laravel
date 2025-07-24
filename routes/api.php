<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v2\SearchController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/login', LoginController::class)->name('v1.login');

function v1Routes()
{
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
}

Route::prefix('v1')->name('v1.')->middleware('auth:sanctum')->group(function () {
    v1Routes();
});

Route::prefix('v2')->name('v2.')->middleware('auth:sanctum')->group(function () {
    v1Routes();
    Route::get('/search', SearchController::class);
});
