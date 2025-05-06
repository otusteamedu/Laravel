<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/html', 'first');

Route::view('/php', 'second', [
    'show' => true,
    'name' => request('name', 'Anon')
]);

Route::view('/blade', 'third', [
    'show' => true,
    'name' => request('name', 'Anon'),
    'users' => ['John', 'Mike', 'Kate']
]);
