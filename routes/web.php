<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');

Route::get('/profile/{userId}', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{userId}', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password/{userId}', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::get('/history/{userId}', [ProfileController::class, 'history'])->name('profile.history');

Route::get('/catalog/{categoryId?}', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/product/{productId}', [CatalogController ::class, 'show'])->name('product');

Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');
Route::post('/chat', [MessageController::class, 'store'])->name('chat.store')->middleware('auth');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/delivery', [CartController::class, 'delivery'])->name('cart.delivery');
Route::post('/cart/pickup', [CartController::class, 'pickup'])->name('cart.pickup');
Route::get('/cart/order', [CartController::class, 'order'])->name('cart.order')->middleware('cart.check');
Route::get('/cart/confirm', [CartController::class, 'confirm'])->name('cart.confirm')->middleware('cart.check');

Route::get('/pay/{orderId}', [App\Ddd\Interface\Controllers\PaymentController::class, 'add'])->name('pay')->middleware('auth');
Route::post('/notification', [App\Ddd\Interface\Controllers\PaymentController::class, 'update'])->name('notification');

Route::view('/admin', 'admin.index')->name('admin.index')->middleware('can:employee-access');

Route::prefix('admin')
    ->name('admin.')
    ->namespace('App\Http\Controllers\Admin')
    ->middleware('can:employee-access')
    ->group(function () {
        Route::get('/categories/export', [App\Http\Controllers\Admin\CategoryController::class, 'export'])
            ->middleware('can:admin-access')
            ->name('categories.export');
        Route::get('/products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])
            ->middleware('can:admin-access')
            ->name('products.export');
        Route::get('/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])
            ->name('orders.export');
        Route::get('/users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])
            ->name('users.export');

        Route::resource('/categories', CategoryController::class)->middleware('can:admin-access');
        Route::resource('/products', ProductController::class)->middleware('can:admin-access');
        Route::resource('/orders', OrderController::class);
        Route::resource('/users', UserController::class);

        Route::get('/payments', [App\Ddd\Interface\Controllers\PaymentController::class, 'index'])->name('payments.index');
    });

Auth::routes();
