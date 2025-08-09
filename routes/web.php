<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
use App\Models\News;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
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

Route::group(['prefix' => '/newsdb'], function(){
    Route::get('/create', function (Request $request) {
        dd(News::create([
            "name" => e($request['name']),
            "text" => e($request['text']),
            "user_id" => (int)$request['user'],
            "link" => Str::slug($request['name']),
            "preview"=> e($request['text']),
            'create_at'=>Carbon::now()->format('Y-m-d')
        ]));
        return;
    });

    Route::get("/update/{id}", function ($id) {
        $post = News::find($id);
        $post->name = "updated title";
        dd($post->save());
        return;
    });

    Route::get("/delete/{id}", function ($id) {
        $news = News::withTrashed()->find($id);
        dd($news->delete());
        return;
    });

    Route::get('/one/{id}', function ($id) {
        $news = News::find($id);
        dd($news);
        dd($news->preview->post);
        return;
    });

    Route::get('/one-all', function () {
        $news = News::all();
        $news->load('preview');
        dd($news);
        foreach ($news as $post) {
            $post->preview;
        }
        return;
    });

    Route::get("/poly/{num}", function ($num) {
        dd(News::find($num));
    });
});