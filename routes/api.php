<?php
/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0"
 * )
 */

use App\Interface\Http\API\V1\AuthController;
use App\Interface\Http\API\V1\CartController;
use App\Interface\Http\API\V1\CategoryController;
use App\Interface\Http\API\V1\OrderController;
use App\Interface\Http\API\V1\PersonalCabinetController;
use App\Interface\Http\API\V1\ProductsController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/items', [CartController::class, 'addItem']);
        Route::put('/items/{item}', [CartController::class, 'updateItem']);
        Route::delete('/items/{item}', [CartController::class, 'removeItem']);
        Route::delete('/clear', [CartController::class, 'clear']);
        Route::post('/transfer-to-user', [CartController::class, 'transferToUser'])->middleware('auth:jwt');
    });

    // Order routes
    Route::apiResource('orders', OrderController::class)->only(['store', 'show']);
    Route::get('orders', [OrderController::class, 'index'])->middleware('auth:jwt');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->middleware('auth:jwt');
});
