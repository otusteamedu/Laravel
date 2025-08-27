<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use ISS\App\Presentation\Http\Controllers\IssStartPageController;
use ISS\App\Presentation\Http\Controllers\IssUserPageController;
use ISS\App\Presentation\Http\Controllers\IssAdminController;
use ISS\App\Presentation\Http\Controllers\IssRoutePointController;
use ISS\App\Presentation\Http\Controllers\AjaxEducationMaterialController;
use ISS\App\Presentation\Http\Controllers\IssCheckExamController;
use ISS\App\Presentation\Http\Controllers\AdminInterface\MainIssUserManageController;
use ISS\App\Presentation\Http\Controllers\AdminInterface\RoutePointManageController;

//Route::prefix('/iss')
Route::prefix('iss2')//config('iss.ISS_ROUTE_PREFIX'))
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
        //проверка экзамена ученика (при отправке на проверку со страницы "точки на учебном маршруте пользователя")
        Route::post('/checkExam', [IssCheckExamController::class, 'checkExam'])->name('issCheckExam');

        //загрузка файла учебного материала
        Route::get('/download/{fileType}/{fileName}', [AjaxEducationMaterialController::class, 'download']);
        Route::get('/open/{fileType}/{fileName}', [AjaxEducationMaterialController::class, 'open']);
    });

    //административный интерфейс
    Route::prefix(config('iss.ISS_ADMIN_ROUTE_PREFIX'))
        ->middleware('issAuthAdmin')->group(function () {
        //страница администратора
        Route::get('/', [IssAdminController::class, 'adminPanel'])->name('issAdmin');

        //работа с пользователями ИОС
        Route::resource('MainIssUserManage', MainIssUserManageController::class);

        //работа со справочной точкой обучающего маршрута
        Route::resource('RoutePointManage', RoutePointManageController::class);

        //инструмент для управления маршрутами пользователей ИОС
        //Route::get('/router', [IssRouterController::class, 'routerShow'])->name('issRouter.show');

        //добавление обучающего маршрута
        //Route::get('/router/route/add', [IssRouterRouteController::class, 'addRoute'])->name('issRouter.addRoute');
        //Route::post('/router/route/add', [IssRouterRouteController::class, 'createRoute'])->name('issRouter.createRoute');
        //редактирование обучающего маршрута
        //Route::get('/router/route/edit', [IssRouterRouteController::class, 'editRoute'])->name('issRouter.editRoute');
        //Route::patch('/router/route/edit/{routeId}', [IssRouterRouteController::class, 'updateRoute'])->name('issRouter.updateRoute');
        //удаление обучающего маршрута
        //Route::get('/router/route/delete', [IssRouterRouteController::class, 'delRoute'])->name('issRouter.delRoute');
        //Route::delete('/router/route/delete/{routeId}', [IssRouterRouteController::class, 'destroyRoute'])->name('issRouter.destroyRoute');

        //добавление реальной точки обучающего маршрута
        //Route::get('router/point/add', [IssRouterPointController::class, 'addPoint'])->name('issRouter.addPoint');
        //Route::post('router/point/add', [IssRouterPointController::class, 'createPoint'])->name('issRouter.createPoint');
        //редактирование реальной точки обучающего маршрута
        //Route::get('/router/point/edit', [IssRouterPointController::class, 'editPoint'])->name('issRouter.editPoint');
        //Route::patch('/router/point/edit/{pointId}', [IssRouterPointController::class, 'updatePoint'])->name('issRouter.updatePoint');
        //удаление реальной точки обучающего маршрута
        //Route::get('/router/point/del', [IssRouterPointController::class, 'delPoint'])->name('issRouter.delPoint');
        //Route::delete('/router/point/del/{pointId}', [IssRouterPointController::class, 'destroyPoint'])->name('issRouter.destroyPoint');

        //добавление пользователя ИОС к обучающему маршруту
        //Route::get('router/user/add', [IssRouterUserController::class, 'addUser'])->name('issRouter.addUser');
        //Route::post('router/user/add/{issUserId}', [IssRouterUserController::class, 'connectUser'])->name('issRouter.connectUser');
        //удаление пользователя ИОС из обучающего маршрута
        //Route::get('/router/user/del', [IssRouterUserController::class, 'delUser'])->name('issRouter.delUser');
        //Route::delete('/router/user/del/{issUserId}', [IssRouterUserController::class, 'unlinkUser'])->name('issRouter.unlinkUser');

        });

    //выход из ИОС
    Route::get('/issExit', [IssStartPageController::class, 'issExit'])->name('issExit');

    //тестовый
    //Route::get('/test', function () {
        // dd(auth()->check(),
        // auth()->user(),
        // session()->all());
        // phpinfo();
        //class_exists('Memcache');
        //extension_loaded('memcache');
        // });
});

//проверка экзамена преподавателем (по защищенной ссылке из письма)
Route::prefix('/iss2')//config('iss.ISS_ROUTE_PREFIX'))
    ->middleware(['web','signed'])
    ->group(function () {
//форма проверки экзамена для преподавателя
Route::get('/checkExam/showCheckForm', [IssCheckExamController::class, 'showCheckExamForm'])
    ->name('showCheckForm');
//обработка результатов проверки преподавателем
Route::post('/checkExam/examCheckResult', [IssCheckExamController::class, 'setExamManualCheckResult'])
    ->name('examCheckResult');
});

/*
Route::get('/sendmail', function () {
    \Illuminate\Support\Facades\Mail::to(' alekseev.a@v2grp.ru')
        ->send(new App\Modules\ISS\src\Mails\IssExamStatusNotify());
});*/
