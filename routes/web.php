<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawController;
use App\Models\News;
use App\Models\User;
use App\Models\NewsPreview;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\TelegramBotHandler;
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
        $newsall = News::all();
        foreach($newsall as $news){
        $arr[] = ['name' => $news->name,
            'preview'=> $news->preview,
            'text' => $news->text,
            'link'=> $news->link,
            'user_id'=>$news->user_id,
            'photo'=> $news->photo,
            'create_at' => $news->create_at];
        }
        $news->load('preview');
        return json_encode($arr,true);
    })->name('news.index');

    Route::get("/poly", function () {
        News::find(1);
    });
});

Route::get('/withdraw', [WithdrawController::class, 'withdraw'])->name('withdraw');

Route::get('/c', function (Request $request) {
    $users = User::all();

    dump($users->map(fn($v) => $v->name));
    dump($users->map->name);
    dump($users->filter(fn($u) => $u->is_admin));
    dump($users->filter->is_admin->map->name);

    return "ok";
});

Route::get('/lazy', function () {
    $users = User::lazy();

    dump($users->all());

    return "ok";
});

Route::get('/file', function () {
    // Storage::put('sub/text.txt','sometext');
    // return 'ok';
    $text = Storage::get('sub/text.txt');
    return dump($text);
});

Route::get('/log', function (Request $request) {
    $qwe = 123;
    $res = ProgTime\TgLogger\TgLogger::sendLog('Debug messages', 'debug');
    dump($res);
    Log::channel('monolog')->info('get info request');
    Log::channel('monolog')->warning('get warn request');
    Log::channel('monolog')->emergency('get warn request');

    return ['ok' => true];
});
Route::post('/upload', function (Request $request) {
    $file = $request->file('avatar');
   // $res = Storage::disk('public')->putFile('avatars',$file);
    $res = Storage::putFileAs(
        'avatars',
        $file,
        'new_name.' . $file->getClientOriginalExtension()
    );

    dump($res);

    return "ok";
})->name('upload');

Route::get('/download', function () {
    $filename = 'avatars/new_name.jpg';
    // abort(404);
    return Storage::download($filename, 'скачай меня.jpg');
});

Route::get('/download/url', function () {
    $filename = 'avatars/new_name.jpg';
    // abort(404);
    return Storage::disk('public')->url($filename);
});
require __DIR__ . '/auth.php';
