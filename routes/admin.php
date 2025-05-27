<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Category;

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
