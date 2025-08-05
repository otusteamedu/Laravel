<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\JWT\AuthController;
use App\Http\Controllers\Oauth\OauthController;

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

Route::group([
    'prefix' => 'oauth'
], function() {
    Route::post('login', [OauthController::class, 'login']);
    Route::post('register', [OauthController::class, 'register']);
});
