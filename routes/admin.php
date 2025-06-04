<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Category;
use App\Http\Controllers\Admin\News;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::prefix('categories')
->name('categories.')
->group(function () {
    Route::get('/', Category\IndexController::class)->name('index');

    Route::get('/create', [Category\CreateController::class, 'create'])->name('create');
    Route::post('/', [Category\CreateController::class, 'store'])->name('store');

    Route::get('/{categoryId}', Category\ShowController::class)->name('show');

    Route::get('/{categoryId}/edit', [Category\UpdateController::class, 'edit'])->name('edit');
    Route::put('/{categoryId}', [Category\UpdateController::class, 'update'])->name('update');

    Route::delete('/{categoryId}', Category\DestroyController::class)->name('destroy');
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
