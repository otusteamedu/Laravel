<?php

use App\Http\Controllers\Projects;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectUsers;
use App\Http\Controllers\Todo;
use App\Http\Controllers\TodoStatuses;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocalizationController;


Route::view('/', 'pages.index')->name('home');

Route::view('/about', 'pages.about')->name('about');

Route::get('locale/{locale}', LocalizationController::class)
    ->name('locale.set');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->prefix('projects')
    ->name('projects.')
    ->group(function () {
        Route::get('/', Projects\Index::class)
            ->name('index');
        Route::get('/create', [Projects\Create::class, 'create'])
            ->name('create')
            ->can('project.create');
        Route::post('/store', [Projects\Create::class, 'store'])
            ->name('store')
            ->can('project.create');
        Route::get('/{projectId}', Projects\Show::class)
            ->name('show');
        //->can('project.view', 'projectId');
        Route::put('/{projectId}', Projects\Update::class)
            ->name('update')
            ->can('project.update', 'projectId');
        Route::delete('/{projectId}', Projects\Delete::class)
            ->name('destroy')
            ->can('project.delete', 'projectId');
    });

Route::middleware('auth')
    ->prefix('project/{projectId}/users')
    ->name('project.users.')
    ->group(function () {
        Route::get('/', ProjectUsers\Index::class)
            ->name('index')
            ->can('project.user.list', 'projectId');
        Route::post('/invite', ProjectUsers\Invite::class)
            ->name('invite')
            ->can('project.user.manage', 'projectId');
        Route::patch('/{userId}/join', ProjectUsers\Join::class)
            ->name('join')
            ->can('project.user.join', ['projectId', 'userId']);
        Route::delete('/{userId}/left', ProjectUsers\Left::class)
            ->name('left')
            ->can('project.user.left', ['projectId', 'userId']);
    });

Route::middleware('auth')
    ->prefix('project/{projectId}/todostatuses')
    ->name('project.todostatuses.')
    ->group(function () {
        Route::get('/', TodoStatuses\Index::class)
            ->name('index')
            ->can('todostatuses.manage', 'projectId');
        Route::post('/store', TodoStatuses\Create::class)
            ->name('store')
            ->can('todostatuses.manage', 'projectId');
        Route::post('/update', TodoStatuses\Update::class)
            ->name('update')
            ->can('todostatuses.manage', 'projectId');
        Route::post('/destroy', TodoStatuses\Delete::class)
            ->name('destroy')
            ->can('todostatuses.manage', 'projectId');
    });

Route::middleware('auth')
    ->prefix('project/{projectId}/todo')
    ->name('project.todos.')
    ->group(function () {
        Route::get('/', Todo\Index::class)
            ->name('index');
        Route::post('/store', Todo\Create::class)
            ->name('store');
        Route::get('/{todoId}', Todo\Show::class)
            ->name('show');
        Route::put('/{todoId}', Todo\Update::class)
            ->name('update');
        Route::delete('/{todoId}', Todo\Delete::class)
            ->name('destroy');
        Route::post('/{todoId}/user-role', Todo\UserRole::class)
            ->name('user-role');
    });

require __DIR__ . '/auth.php';
