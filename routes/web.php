<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
Route::get('/', function () {
    return view('main',
        [
            'h1'=>'Заголовок главной страницы',
            'text'=>'Текст главной страницы'
        ]
    );
});
Route::get('/user/', function () {
    return view('web/user/show',
        [
            'name'=>'Иван',
            'date'=>date("Y-m-d"),
            "group"=>"Обычный пользователь",
            "text"=>"Рыба текст"
        ]
    );
});
Route::get('/auth/', function () {
    return view('web/user/auth',
        [
            'name'=>'Иван',
            'date'=>date("Y-m-d"),
            "group"=>"Обычный пользователь",
            "text"=>"Рыба текст"
        ]
    );
});
Route::get('/news/', function () {
    return view('web/content/news',
        [
            'h1'=>'Заголовок новости',
            'text'=>'Текст новости'
        ]
    );
});

Route::get('/test/', [Test::class, 'test']);