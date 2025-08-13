<?php

use App\Http\API\V1\AuthController;
use App\Http\API\V1\CategoryController;
use App\Http\API\V1\ProductsController;
use App\Http\API\V2\OauthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::group([

    //'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});



Route::group([
    'prefix' => 'v1',
    'name' => 'v1',
    'middleware' => ['auth:jwt'],
], function(){
    Route::apiResource('/products', ProductsController::class);
    Route::apiResource('/categories', CategoryController::class);
});


Route::group(['prefix' => 'v2'], function() {

    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [OauthController::class, 'register']);
        Route::post('login', [OauthController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function() {

        Route::apiResource('/products', ProductsController::class)
            ->middleware(CheckForAnyScope::using('product:admin'));


        Route::apiResource('/categories', CategoryController::class)
            ->middleware(CheckForAnyScope::using('category:admin'));

    });
});


//Route::apiResource('/products', ProductsController::class);
