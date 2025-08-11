<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\News;

require __DIR__.'/auth.php';
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/send-notification', \App\Http\Controllers\Notifications\Send::class);

Route::prefix('news')
    ->name('news.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', News\Index::class)
            ->name('index');
        Route::get('/page/{num}', [News\Index::class,'pagination'])
            ->name('indexpage');
        Route::get('/create', [News\Create::class, 'create'])
            ->name('create');
        Route::post('/', [News\Create::class, 'creates'])
            ->name('store');
        Route::get('/{news}', News\Show::class)
            ->name('show');
        Route::get('/{newsId}/edit', [News\Update::class, 'edit'])
            ->name('edit');
        Route::put('/{newsId}', [News\Update::class, 'update'])
            ->name('update');
        Route::get('/{newsId}/destroy', [News\Delete::class,'delete'])
            ->name('destroy');
    });