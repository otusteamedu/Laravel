
<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\OauthController;
use Laravel\Passport\Http\Middleware\CheckScopes;

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [OauthController::class, 'register']);
        Route::post('login', [OauthController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function() {
        //Route::apiResource('news', NewsController::class);
        Route::get('news', [NewsController::class,'index']);
        Route::get('news/{id}', [NewsController::class,'show']);
        Route::put('news/create', [NewsController::class,'create']);
        Route::delete('news/{id}', [NewsController::class,'destroy']);
        Route::post('news/{id}', [NewsController::class,'update']);
        Route::get('test_scope', [NewsController::class, 'testScope'])
            ->middleware(CheckScopes::using(['news:create']));
    });

});