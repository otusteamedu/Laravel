<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Modules\ISS\src\Http\Controllers\IssStartPageController;
use App\Modules\ISS\src\Http\Controllers\IssUserPageController;
use App\Modules\ISS\src\Http\Controllers\IssAdminController;
use App\Modules\ISS\src\Http\Controllers\IssRoutePointController;
use App\Modules\ISS\src\Http\Controllers\AjaxEducationMaterialController;
use App\Modules\ISS\src\Http\Controllers\IssCheckExamController;

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
        //проверка экзамена ученика (при отправке на проверку со страницы "точки на учебном маршруте пользователя")
        Route::post('/checkExam', [IssCheckExamController::class, 'checkExam'])->name('issCheckExam');

        //загрузка файла учебного материала
        Route::get('/download/{fileType}/{fileName}', [AjaxEducationMaterialController::class, 'download']);
        Route::get('/open/{fileType}/{fileName}', [AjaxEducationMaterialController::class, 'open']);
    });

    //административный интерфейс
    Route::prefix('/admin')
        ->middleware('issAuthAdmin')->group(function () {
        //страница администратора
        Route::get('/', [IssAdminController::class, 'adminPanel'])->name('issAdmin');

        //добавление нового пользователя ИОС
        //Route::get('/addIssUser', [IssAdminController::class, 'addIssUser'])->name('issAdmin.add');
        //Route::post('/addIssUser', [IssAdminController::class, 'createIssUser'])->name('issAdmin.create');
        //редактирование данных пользователя ИОС
        //Route::get('/updateIssUser', [IssAdminController::class, 'editIssUser'])->name('issAdmin.edit');
        //Route::post('/updateIssUser/{issUserId}', [IssAdminController::class, 'updateIssUser'])->name('issAdmin.update');
        //удаление пользователя ИОС
        //Route::get('/deleteIssUser', [IssAdminController::class, 'deleteIssUser'])->name('issAdmin.delete');
        //Route::post('/deleteIssUser/{issUserId}', [IssAdminController::class, 'destroyIssUser'])->name('issAdmin.destroy');


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

        //добавление справочной точки обучающего маршрута
        //Route::get('router/refPoint/add', [IssRouterRefPointController::class, 'addRefPoint'])->name('issRouter.addRefPoint');
        //Route::post('router/refPoint/add', [IssRouterRefPointController::class, 'createRefPoint'])->name('issRouter.createRefPoint');
        //редактирование справочной точки обучающего маршрута
        //Route::get('/router/refPoint/edit', [IssRouterRefPointController::class, 'editRefPoint'])->name('issRouter.editRefPoint');
        //Route::patch('/router/refPoint/edit/{refPointId}', [IssRouterRefPointController::class, 'updateRefPoint'])->name('issRouter.updateRefPoint');
        //удаление справочной точки обучающего маршрута
        //Route::get('/router/refPoint/del', [IssRouterRefPointController::class, 'delRefPoint'])->name('issRouter.delRefPoint');
        //Route::delete('/router/refPoint/del/{refPointId}', [IssRouterRefPointController::class, 'destroyRefPoint'])->name('issRouter.destroyRefPoint');

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
Route::prefix('/iss')
    ->middleware(['web',/*'signed'*/])
    ->group(function () {
//форма проверки экзамена для преподавателя
Route::get('/checkExam/showCheckForm', [IssCheckExamController::class, 'showCheckExamForm']);
//обработка результатов проверки преподавателем
Route::post('/checkExam/examCheckResult', [IssCheckExamController::class, 'setExamManualCheckResult'])
    ->name('examCheckResult');
});
