<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ru'])) {
        abort(400);
    }

    App::setLocale($locale);
    return view('home');
});

Route::get('/user', function () {
    return view('user');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/static', function () {
    return view('static');
});
