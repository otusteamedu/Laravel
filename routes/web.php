<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/about', 'about')->name('about');

Route::view('/profile', 'profile')->name('profile');

Auth::routes();
