<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('/user', 'user', [
    'user_name' => request('name', 'John Doe'),
    'position' => request('position', 'Full Stack Developer'),
    'address' => request('address', 'New York, USA'),
])->name('user');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/static', function () {
    return view('static');
})->name('static');
