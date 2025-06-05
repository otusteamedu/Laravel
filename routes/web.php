<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Projects;
use App\Http\Controllers\ProjectUsers;
use App\Http\Controllers\TodoStatuses;

Route::view('/', 'pages.index')->name('home');

Route::view('/about', 'pages.about')->name('about');

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
        Route::post('/', [Projects\Create::class, 'store'])
            ->name('store')
            ->can('project.create');
        Route::get('/{projectId}', Projects\Show::class)
            ->name('show')
            ->can('project.view', 'projectId');
        Route::get('/{projectId}/edit', [Projects\Update::class, 'edit'])
            ->name('edit')
            ->can('project.update', 'projectId');
        Route::put('/{projectId}', [Projects\Update::class, 'update'])
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
            ->name('index');
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

require __DIR__ . '/auth.php';
