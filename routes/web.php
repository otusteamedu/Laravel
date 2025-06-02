<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');

Route::get('/profile/{userId}', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{userId}', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password/{userId}', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::get('/history/{userId}', [ProfileController::class, 'history'])->name('profile.history');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/category/{categoryId}', [CatalogController::class, 'category'])->name('category');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

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
