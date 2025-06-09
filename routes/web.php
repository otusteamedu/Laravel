<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EditUserFioController;
use App\Http\Controllers\MainAppStartPageController;

use App\Modules\ISS\src\Http\Controllers\IssStartPageController;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [MainAppStartPageController::class, 'index'])->name('main');

Route::middleware('auth')->group(function () {

    //админ интерфейс защищен гейтом (доступен только для Роли = admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->can('isAdmin', '\App\Models\User')
        ->name('dashboard');

    //редактирование данных пользователя (!!! только для ДЗ №6 как пример -- из курсового проекта УДАЛИТЬ!!!)
    // Пользователь может редактировать только свои данные. Админ -- может редакитровать все.
    Route::get('/editUserOfMainUp/{userForEditId}', [EditUserFioController::class, 'edit'])
        ->can('editFio', 'userForEditId')
        ->name('editUserOfMainUp');
    Route::post('/updateUserOfMainUp/{userForEditId}', [EditUserFioController::class, 'update'])
        ->can('editFio', 'userForEditId')
        ->name('updateUserOfMainUp');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
