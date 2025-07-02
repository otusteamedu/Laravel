<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Categories;
use App\Http\Controllers\Admin\News;
use App\Http\Controllers\Admin\Users;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::prefix('categories')
->name('categories.')
->group(function () {
    Route::get('/', Categories\IndexController::class)->name('index');

    Route::get('/create', [Categories\CreateController::class, 'create'])->name('create');
    Route::post('/', [Categories\CreateController::class, 'store'])->name('store');

    Route::get('/{categoryId}', Categories\ShowController::class)->name('show');

    Route::get('/{categoryId}/edit', [Categories\UpdateController::class, 'edit'])->name('edit');
    Route::put('/{categoryId}', [Categories\UpdateController::class, 'update'])->name('update');

    Route::delete('/{categoryId}', Categories\DestroyController::class)->name('destroy');
});


Route::prefix('news')
     ->name('news.')
     ->group(function () {
         Route::get('/', News\IndexController::class)->name('index');

         Route::get('/create', [News\CreateController::class, 'create'])->name('create');
         Route::post('/', [News\CreateController::class, 'store'])->name('store');

         Route::get('/{newsId}', News\ShowController::class)->name('show');

         Route::get('/{newsId}/edit', [News\UpdateController::class, 'edit'])->name('edit');
         Route::put('/{newsId}', [News\UpdateController::class, 'update'])->name('update');

         Route::delete('/{newsId}', News\DestroyController::class)->name('destroy');
     });


Route::prefix('users')
     ->name('users.')
     ->group(function () {
         Route::get('/', Users\IndexController::class)->name('index');

         Route::get('/create', [Users\CreateController::class, 'create'])->name('create');
         Route::post('/', [Users\CreateController::class, 'store'])->name('store');

         Route::get('/{userId}', Users\ShowController::class)->name('show');

         Route::get('/{userId}/edit', [Users\UpdateController::class, 'edit'])->name('edit');
         Route::put('/{userId}', [Users\UpdateController::class, 'update'])->name('update');

         Route::delete('/{userId}', Users\DestroyController::class)->name('destroy');
     });
