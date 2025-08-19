<?php

use App\Http\API\V1\AuthController;
use App\Http\API\V1\CategoryController;
use App\Http\API\V1\PersonalCabinetController;
use App\Http\API\V1\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('personal-cabinet')->group(function () {
        Route::get('/', [PersonalCabinetController::class, 'index']);
        Route::put('/', [PersonalCabinetController::class, 'update']);
        Route::delete('/', [PersonalCabinetController::class, 'destroy']);
    });
});


//Route::apiResource('/products', ProductsController::class);
