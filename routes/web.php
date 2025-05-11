<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::view('/blade', 'third', [
//     'show' => true, 
//     'name' => request('name', 'Sergey'),
//     'users' => ["John", 'Mike', 'Kate']
// ]);

Route::view('/page', 'layouts.main');
Route::view('/reg', 'layouts.reg');
Route::view('/abstr', 'layouts.abstr');
Route::view('/user', 'layouts.user',[
    'name' => request ('name', 'Ivan'),
    'users' => [
        ['name' => 'Ivan', 'age' => 33, 'city' => 'Moscow'],
        ['name' => 'Petr', 'age' => 25, 'city' => 'Volgograd'],
        ['name' => 'Maria', 'age' => 41, 'city' => 'Novosibirsk'],
        ['name' => 'Elena', 'age' => 35, 'city' => 'Samara'],
        ['name' => 'Irina', 'age' => 29, 'city' => 'Tula'],
    ],

]);