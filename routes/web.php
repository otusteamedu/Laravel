<?php

use Illuminate\Support\Facades\Route;
use App\Models\Todolist;
use App\Models\User;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => '/e'], function () {

    Route::get('/create', function () {
        $todolist = new Todolist();

        $todolist->title = "123";
        $todolist->text = "456";
        $todolist->dedline = "02.06.2025";
        $todolist->author_id = 2;

        $todolist->save();

        dump($todolist->save());
        dump($todolist);

        echo PHP_EOL . " ===================================  " . PHP_EOL;

        dump(Todolist::create([
            "title" => fake()->name,
            "text" => fake()->sentence,
            "dedline" => fake()->date(),
            "author_id" => random_int(1,10),
        ]));

        return "";
    });

    Route::get("/update", function () {
        $todolist = Todolist::find(1);
        $todolist->title = "123updated title";

        dump($todolist->save());

        return "";
    });

    Route::get("/delete", function () {
        $todolist = Todolist::find(2);
        //dump($todolist->trashed());
        dump($todolist->delete());

        return "";
    });


    Route::get("/users", function () {
        $user = User::find(1);
        //dump($todolist->trashed());
        dump($user);

        return "";
    });

});

