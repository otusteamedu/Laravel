<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'home')->name('page');
Route::view('/reg', 'reg')->name('reg');
Route::view('/about', 'about')->name('about');

Route::view('/user', 'user', [
    'name' => request('name', 'Ivan'),
    'users' => [
        ['name' => 'Ivan', 'age' => 33, 'city' => 'Moscow'],
        ['name' => 'Petr', 'age' => 25, 'city' => 'Volgograd'],
        ['name' => 'Maria', 'age' => 41, 'city' => 'Novosibirsk'],
        ['name' => 'Elena', 'age' => 35, 'city' => 'Samara'],
        ['name' => 'Irina', 'age' => 29, 'city' => 'Tula'],
    ],

])->name('user');
