<?php

use Illuminate\Support\Facades\Route;
use App\Http\API\V1\Controllers\AuthController;
use App\Http\API\V1\Controllers\ProjectController;
use App\Http\API\V1\Controllers\TodoStatusController;

Route::middleware('auth:api')
    ->prefix('/v1/projects')
    ->name('api.projects.')
    ->group(function () {
        Route::post('/store', [ProjectController::class, 'store'])
            ->name('store')
            ->can('project.create');
    });

Route::middleware('auth:api')
    ->prefix('/v1/project/{projectId}/todostatuses')
    ->name('api.todo-status.')
    ->group(function () {
        Route::get('/', [TodoStatusController::class, 'index'])
            ->name('index')
            ->can('todostatuses.manage', 'projectId');;
        Route::post('/store', [TodoStatusController::class, 'store'])
            ->name('store')
            ->can('todostatuses.manage', 'projectId');
        Route::get('/{statusId}', [TodoStatusController::class, 'show'])
            ->name('show')
            ->can('todostatuses.manage', 'projectId');
        Route::patch('/{statusId}', [TodoStatusController::class, 'update'])
            ->name('update')
            ->can('todostatuses.manage', 'projectId');
        Route::delete('/{statusId}', [TodoStatusController::class, 'destroy'])
            ->name('destroy')
            ->can('todostatuses.manage', 'projectId');
    });

Route::middleware('api')
    ->prefix('/v1/auth')
    ->group(function ($router) {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('user', [AuthController::class, 'user']);
    });
