<?php

use App\Modules\ISS\src\Http\Controllers\Api\v1\UserDataController;
use App\Modules\ISS\src\Http\Controllers\Api\v1\EducationRouteController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;

Route::group(
    [
        'prefix' => config('iss.ISS_ROUTE_PREFIX').'/api/v1',
        'name' => 'v1',
        //'middleware' => ['api', 'auth:jwt'],
        'middleware' => ['api'],
        ],
    function () {
        //CRUD операции для пользователя ИОС (защита JWT)
        Route::apiResource('/issUser', UserDataController::class)->middleware('auth:jwt');

        //CRUD операции для справочного обучающего маршрута ИОС (защита Passport)
        Route::apiResource('/issEducationRoute', EducationRouteController::class)
            ->middleware('auth:api');

        //проверка scope (только для демонстрации ДЗ)
        Route::get('/test/scopes', function () { return json_encode(['ok', 200]); })
            ->middleware('auth:api', CheckToken::using(['educationRoute:create'])); //должен дать 401 т.к. такой токен не выдается в login
    }
);



