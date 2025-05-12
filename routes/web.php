<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

Route::view('/', 'home');
Route::view('/register', 'register');
Route::view('/about', 'about');

Route::get('/user', function (Request $request) {
    $name = $request->query('name');
    $email = $request->query('email');
    $age = $request->query('age');
    $bio = $request->query('bio');

    $users = [
        [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'age' => 30,
            'bio' => 'Программист с 5-летним опытом работы в Laravel.',
        ],
        [
            'name' => 'Мария Смирнова',
            'email' => 'maria@example.com',
            'age' => 27,
            'bio' => 'UX-дизайнер и фронтенд-разработчик.',
        ],
        [
            'name' => 'Петр Кузнецов',
            'email' => 'petr@example.com',
            'age' => 35,
            'bio' => 'Менеджер проектов и преподаватель.',
        ],
    ];

    return view('user', compact('name', 'email', 'age', 'bio', 'users'));
})->name('user');