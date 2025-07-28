<?php

use App\Http\Controllers\Api\v1\CarsController;
use App\Http\Controllers\Api\v1\OauthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [OauthController::class, 'register']);
        Route::post('login', [OauthController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function() {
        Route::apiResource('cars', CarsController::class);

        Route::get('test_scope', [CarsController::class, 'testScope'])
            ->middleware(CheckToken::using(['cars:create']));
    });
});
