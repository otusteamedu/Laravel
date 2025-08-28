<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main',
        [
            'h1'=>'Laravel',
            'text'=>'Текст главной страницы'
        ]
    );
});

Route::get('/user/edit', function () {
    return view('web/user/edit',
        [
            'name'=>'Иван',
            'date'=>date("Y-m-d"),
            "group"=>"Обычный пользователь",
            "text"=>"Рыба текст"
        ]
    );
});

Route::get('/user', function () {
    return view('web/user/show',
        [
            'name'=>'Иван',
            'date'=>date("Y-m-d"),
            "group"=>"Обычный пользователь",
            "text"=>"Рыба текст",
            'photo'=>'https://s.cq.ru/img/f/e/2023/11/08/17698.jpg'
        ]
    );
});

Route::post('/auth', function () {
    return view('web/user/auth',
        [
            'name'=>'Иван',
            'date'=>date("Y-m-d"),
            "group"=>"Обычный пользователь",
            "text"=>"Рыба текст"
        ]
    );
})->name('login');
Route::get('/news', function () {
    return view('web/content/news',
        [
            'pagination'=>[
                'count'=>10,
                'page'=>2
            ],
            'news'=>
            [
                [
                    'name'=>'Заголовок новости',
                    'text'=>'Текст новости',
                    'date'=>date("Y-m-d"),
                    'user'=>'My User',
                    'photo'=>'https://s.cq.ru/img/f/e/2023/11/08/17698.jpg'
                ],
                [
                    'name'=>'Заголовок новости2',
                    'text'=>'Текст новости2',
                    'date'=>date("Y-m-d"),
                    'user'=>'My User',
                    'photo'=>'https://s.cq.ru/img/f/e/2023/11/08/17698.jpg'
                ],
                [
                    'name'=>'Заголовок новости3',
                    'text'=>'Текст новости3',
                    'date'=>date("Y-m-d"),
                    'user'=>'My User',
                    'photo'=>'https://s.cq.ru/img/f/e/2023/11/08/17698.jpg'
                ],
                [
                    'name'=>'Заголовок новости',
                    'text'=>'Текст новости',
                    'date'=>date("Y-m-d"),
                    'user'=>'My User',
                    'photo'=>'https://s.cq.ru/img/f/e/2023/11/08/17698.jpg'
                ]
            ]
        ]
    );
});