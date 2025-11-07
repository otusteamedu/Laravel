<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return ['ok' => true, 'data' => ['message' => 'hello world']];
});

Route::get('/hello.txt', function () {
    return "<h1>Hello world</h1>";
});
