<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Models\Todolist;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/e', function (Request $request) {
    dump(Todolist::create([
        "title" => "task1",
        "text" => "do my laravel homework",
        "dedline" => "02.06.2025",
        "author_id" => 1,
    ]));

return "";
});
