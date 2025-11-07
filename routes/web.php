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

Route::view("/first", "first");
Route::view(
    "/second",
    "second",
    ["name" => request("name")]
);

Route::view(
    "/third",
    "third",
    [
        "names" => ["John", "Kate", "Mike"]
    ]
);
