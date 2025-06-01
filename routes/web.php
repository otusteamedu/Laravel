<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Projects;
use App\Http\Controllers\TodoStatuses;
use App\Http\Controllers\TodoController;

Route::view('/', 'pages.index')->name('home');

Route::view('/about', 'pages.about')->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/todos', [TodoController::class, 'list'])->name('todo.list');
});


Route::middleware('auth')
    ->prefix('projects')
    ->name('projects.')
    ->group(function () {
        Route::get('/', Projects\Index::class)
            ->name('index');
        Route::get('/create', [Projects\Create::class, 'create'])
            ->name('create');
        Route::post('/', [Projects\Create::class, 'store'])
            ->name('store');
        Route::get('/{projectId}', Projects\Show::class)
            ->name('show');
        Route::get('/{projectId}/edit', [Projects\Update::class, 'edit'])
            ->name('edit');
        Route::put('/{projectId}', [Projects\Update::class, 'update'])
            ->name('update');
        Route::delete('/{projectId}', Projects\Delete::class)
            ->name('destroy');
    });

Route::middleware('auth')
    ->prefix('project/{projectId}/todostatuses')
    ->name('project.todostatuses.')
    ->group(function () {
        Route::get('/', TodoStatuses\Index::class)
            ->name('index');
        Route::post('/store', TodoStatuses\Create::class)
            ->name('store');
        Route::post('/update', TodoStatuses\Update::class)
            ->name('update');
        Route::post('/destroy', TodoStatuses\Delete::class)
            ->name('destroy');
    });
