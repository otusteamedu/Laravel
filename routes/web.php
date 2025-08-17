<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawController;
use App\Models\News;
use App\Models\NewsPreview;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
Route::get('/', function () {
    return view('welcome');
});

Route::view('/page', 'page');

Route::resource('news', NewsController::class)->middleware('auth');

Route::get('/dashboard', function (Request $request) {
    $locale = mb_substr($request->headers->get('accept-language'), 0, 2);
    App::setLocale($locale);

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/withdraw', [WithdrawController::class, 'withdraw'])->name('withdraw');

Route::group(['prefix' => '/e'], function () {
    Route::post('/create', function (Request $request) {
        $name = 'test name';
        $photo = "https://via.placeholder.com/320x250.png/004433?text=quaerat";
        dump(News::create([
            "name" => $name,
            "text" => 'test text new line',
            'preview'=> 'test text new line',
            'link'=> Str::slug($name),
            'photo'=> $photo,
            "user_id" => 1,
        ]));
        return "";
    })->name('news.create');

    Route::get("/update", function () {
        $news = News::find(1);
        $news->name = "12updated title";
        dump($news->save());
        return "";
    });

    Route::get("/delete", function () {
        $news = News::withTrashed()->find(2);
        dump($news->trashed());
        dump($news->restore());
        return "";
    });

    Route::get('/one', function () {
        $news = News::find(20);
        return json_encode($news,true);
    });

    Route::get('/one-all', function () {
        $news = News::all();
        $news->load('preview');
        return json_encode($news,true);
    })->name('news.index');

    Route::get("/poly", function () {
        News::find(1);
    });
});

require __DIR__ . '/auth.php';
