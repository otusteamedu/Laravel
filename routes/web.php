<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/admin', 'home')->name('home');
Route::view('/create', 'create')->name('create');
