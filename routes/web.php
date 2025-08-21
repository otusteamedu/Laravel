<?php

use App\Http\Controllers\RoutController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

Route::view('/page', 'page');

Route::resource('news', NewsController::class)->middleware('auth');

Route::get('/dashboard', function (Request $request) {
    $locale = mb_substr($request->headers->get('accept-language'), 0, 2);
    App::setLocale($locale);

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test', function (AuthController $authController) {
    return ['status' => 'success'];
});
Route::get('/{domain}/{method}/{essence}', [RoutController::class,'index'])
            ->name('index');
