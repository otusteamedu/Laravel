<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('layouts.main');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('area', AreaController::class);

Route::middleware(['auth'])->group(function () {

    Route::resource('mesaure', MeasureController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/fibonachi', function () {
        return view('fibonachi');
    })->name('fibonachi');