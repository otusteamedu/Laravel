<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\JWT\AuthController;

Route::group(['middleware' => 'auth:jwt'], function () {
    Route::get('/tmp3', function() {return User::all()->toJson();});
});


Route::group([
    //'middleware' => 'api',
    'prefix' => 'jwt'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
