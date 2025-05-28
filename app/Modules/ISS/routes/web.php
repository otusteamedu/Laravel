<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Modules\ISS\src\Http\Controllers\IssStartPageController;
use App\Modules\ISS\src\Http\Controllers\IssUserPageController;
use App\Modules\ISS\src\Http\Controllers\IssRoutePointController;

//главная страница модуля
Route::get('/iss', [IssStartPageController::class, 'index'])
    ->middleware('web')->name('iss');

//ВРЕМЕННЫЙ маршрут на стр пользователя, пока нет авторизации
Route::get('/iss/user/{id}', [IssUserPageController::class, 'userAccount'])
    ->middleware('web')->name('issUser');

//маршрут на страницу точки маршрута обучения
Route::get('/iss/educationRoutePoint/{routeId}/{pointId}', [IssRoutePointController::class, 'educationRoutePoint'])
    ->middleware('web')->name('issEducationRoutePoint');
