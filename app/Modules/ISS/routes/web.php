<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Modules\ISS\src\Http\Controllers\IssStartPageController;
use App\Modules\ISS\src\Http\Controllers\IssUserPageController;
use App\Modules\ISS\src\Http\Controllers\IssAdminController;
use App\Modules\ISS\src\Http\Controllers\IssRoutePointController;

Route::prefix('/iss')
    ->middleware(['web', 'auth'])
    ->group(function () {

    //главная страница модуля
    Route::get('/', [IssStartPageController::class, 'index'])
        ->name('iss');

    Route::middleware('issAuthUser')->group(function () {
        //страница пользователя
        Route::get('/user/{issUserId}', [IssUserPageController::class, 'userAccount'])
            ->name('issUser');

        //страница точки на учебном маршруте пользователя
        Route::get(
            '/educationRoutePoint/{issUserId}/{routeId}/{pointId}',
            [IssRoutePointController::class, 'educationRoutePoint']
        )->name('issEducationRoutePoint');
    });

    //административный интерфейс
    Route::middleware('issAuthAdmin')->group(function () {
        //страница администратора
        Route::get('/admin', [IssAdminController::class, 'adminPanel'])->name('issAdmin');
        //добавление нового пользователя ИОС
        //Route::get('/admin/addIssUser', [IssAdminController::class, 'addIssUser'])->name('issAdminAdd.add');
        //Route::post('/admin/addIssUser', [IssAdminController::class, 'createIssUser'])->name('issAdminAdd.create');
        //редактирование данных пользователя ИОС
        //Route::get('/admin/updateIssUser', [IssAdminController::class, 'editIssUser'])->name('issAdminEdit.edit');
        //Route::post('/admin/updateIssUser', [IssAdminController::class, 'updateIssUser'])->name('issAdminEdit.update');
        //удаление пользователя ИОС
        //Route::get('/admin/deleteIssUser', [IssAdminController::class, 'deleteIssUser'])->name('issAdminDelete.delete');
        //Route::post('/admin/deleteIssUser', [IssAdminController::class, 'destroyIssUser'])->name('issAdminDelete.destroy');

        //инструмент для управления маршрутами пользователей ИОС
        //Route::get('/admin/router', [IssAdminController::class, 'routerShow'])->name('issAdminRouterShow');
    });

    //выход из ИОС
    Route::get('/issExit', [IssStartPageController::class, 'issExit'])->name('issExit');

    //тестовый
    //Route::get('/test', function () { dd(auth()->check(), auth()->user(), session()->all()); });
});
