<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\WithdrawController;
use App\Models\News;
use App\Http\Controllers;
use App\Models\User;
use App\Models\NewsPreview;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\TelegramBotHandler;
use App\Http\Middleware\CheckLocale;
use App\Services\JobService;
use Illuminate\Http\Response as HttpResponse;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/page', 'page');

//Route::resource('news', NewsController::class)->middleware('auth');

Route::prefix('news')
    ->name('news.')
    ->group(function () {
        Route::get('/', Controllers\News\Index::class)
            ->name('index');
        Route::get('/page/{num}', [Controllers\News\Index::class,'pagination'])
            ->name('indexpage');
        Route::get('/create', [Controllers\News\Create::class, 'create'])
            ->name('create');
        Route::post('/', [Controllers\News\Create::class, 'creates'])
            ->name('store');
        Route::get('/{news}', Controllers\News\Show::class)
            ->name('show');
        Route::get('/{newsId}/edit', [Controllers\News\Update::class, 'edit'])
            ->name('edit');
        Route::put('/{newsId}', [Controllers\News\Update::class, 'update'])
            ->name('update');
        Route::get('/{newsId}/destroy', [Controllers\News\Delete::class,'delete'])
            ->name('destroy');
    });

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
    Route::get('/create', function (Request $request) {
        $width=320;
        $height=250;
        $name = fake()->name;
        $arr = [
            'name' => $name,
            'preview'=> fake()->sentence,
            'text' => fake()->paragraph,
            'link'=> Str::slug($name),
            'user_id'=>10,
            'is_admin'=>1,
            'photo'=> fake()->imageUrl($width, $height),
            'create_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
        dump(News::create($arr));
        JobService::add('Добавлена запись в таблицу');
        return "";
    })->name('news.create');

    Route::get("/update", function (Request $request) {
        $id = $request->get('id');
        if($id){
            $news = News::find($id);
            if($news){
                $news->name = "12updated title";
                JobService::add('Обновлена запись в таблице');
                dump($news->save());
            }
        }
        return "";
    });

    Route::get("/delete", function (Request $request) {
        $id = $request->get('id');
        if($id){
            $news = News::find($id);
            dump($news->trashed());
            dump($news->restore());
            JobService::add('Удалена запись в таблице');
        }
        return "";
    });

//     Route::get('/one', function () {
//         $news = News::find(20);
//         return json_encode($news,true);
//     });

//     Route::get('/one-all', function () {
//         $newsall = News::all();
//         foreach($newsall as $news){
//         $arr[] = ['name' => $news->name,
//             'preview'=> $news->preview,
//             'text' => $news->text,
//             'link'=> $news->link,
//             'user_id'=>$news->user_id,
//             'photo'=> $news->photo,
//             'create_at' => $news->create_at];
//         }
//         $news->load('preview');
//         return json_encode($arr,true);
//     })->name('news.index');

//     Route::get("/poly", function () {
//         News::find(1);
//     });
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

Route::middleware([CheckLocale::class])->group(function () {
    Route::get('/{locale}/locale', [LocaleController::class, 'show']);
});
Route::get('/file', function () {
    // Storage::put('sub/text.txt','sometext');
    // return 'ok';
    $text = Storage::get('sub/text.txt');
    return dump($text);
});
Route::get('/sendtg', function (Request $request) {
    $text = $request->get('text');
    if($text){
        $job = new \App\Jobs\SendTgJobs(
            $text,
            'debug'
        );
        dispatch($job)->onQueue('telegram')->afterResponse();
        return new HttpResponse('ok');
    }
    else{
        return new HttpResponse('Не указан get параметр text');
    }
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
//require __DIR__ . '/auth.php';
require __DIR__ . '/cached.php';
