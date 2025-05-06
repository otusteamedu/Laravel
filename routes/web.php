<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "<h1>Hello world</h1>";
});
Route::get('/json', function () {
    return ["ok" => true, "data" => ["name" => "laravel"]];
});

Route::get("/dfd", function () {
    return view("");
});