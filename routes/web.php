<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');
Route::view('/profile', 'profile')->name('profile');

Route::view('/admin', 'admin.index')->name('admin.index')->middleware('can:employee-access');

Route::prefix('admin')
    ->name('admin.')
    ->namespace('App\Http\Controllers\Admin')
    ->middleware('can:employee-access')
    ->group(function () {
        Route::resource('/categories', CategoryController::class)->middleware('can:admin-access');
        Route::resource('/products', ProductController::class)->middleware('can:admin-access');
        Route::resource('/orders', OrderController::class);
        Route::resource('/users', UserController::class);
    });

Auth::routes();
