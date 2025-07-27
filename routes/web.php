<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;

Route::get('/test-logs', TestLogController::class);


Route::group([
        'prefix' => '{locale?}',
        'where' => ['locale' => implode('|', config('app.supported_locales'))]
    ],
    function () {
        Route::get('/', function () {
            return view('welcome');
        });
});

Route::get('/setPrice', function () {

    $product = App\Models\Product::find(1);
    $product->price = 180.00;
    $product->save();

    return $product->price;
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
