<?php

use Illuminate\Support\Facades\Route;
use App\Http\API\V1\Controllers\TodoStatusController;

Route::prefix('project/{projectId}/todostatuses')
    ->name('api.todo-status.')
    ->group(function () {
        Route::get('/', [TodoStatusController::class, 'index'])
            ->name('index');
        Route::post('/store', [TodoStatusController::class, 'store'])
            ->name('store');
        Route::get('/{statusId}', [TodoStatusController::class, 'show'])
            ->name('show');
        Route::patch('/{statusId}', [TodoStatusController::class, 'update'])
            ->name('update');
        Route::delete('/{statusId}', [TodoStatusController::class, 'destroy'])
            ->name('destroy');
    });
