<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawController;
use App\Models\Post;
use App\Models\PostPreview;
use App\Models\User;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/page', 'page');

Route::resource('posts', PostController::class)->middleware('auth');

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

Route::get('/qb', function (Request $request) {
    $res = \App\Repo\UserRepo::getTopUsers();

    dump($res);

    return $res;
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
    $text = Storage::exists('sub/text.txt');
    return dump($text);
});

Route::get('/log', function (Request $request) {
    $qwe = 123;
    Log::channel('syslog')->info('get info request');
    Log::channel('syslog')->warning('get warn request');
    Log::channel('syslog')->emergency('get warn request');

    return ['ok' => true];
});

Route::post('/upload', function (Request $request) {
    $file = $request->file('avatar');

    $res = Storage::putFileAs(
        'avatars',
        $file,
        'new_name.' . $file->getClientOriginalExtension()
    );

    dump($res);

    return "ok";
})->name('upload');

Route::get('/download', function () {
    $filename = 'avatars/new_name.png';
    // abort(404);
    return Storage::download($filename, 'скачай меня.png');
});

Route::get('/download/url', function () {
    $filename = 'avatars/new_name.png';
    // abort(404);
    return Storage::disk('public')->url($filename);
});

Route::group(['prefix' => '/e'], function () {
    Route::get('/create', function (Request $request) {
        // $post = new Post();
        // $post->title = "new post";
        // $post->text = "new text";
        // $post->is_draft = true;
        // $post->author_id = 2;

        dump(Post::create([
            "title" => "2new post",
            "text" => "2new text",
            "is_draft" => false,
            "author_id" => 2,
        ]));

        // dump($post->save());

        return "";
    });

    Route::get("/update", function () {
        $post = Post::find(1);
        $post->title = "12updated title";

        dump($post->save());

        return "";
    });

    Route::get("/delete", function () {
        $post = Post::withTrashed()->find(2);

        dump($post->trashed());

        dump($post->restore());

        return "";
    });

    Route::get('/one', function () {
        $post = Post::find(3);

        // $preview = new PostPreview();
        // $preview->photo_url = 'qwe';

        // $post->preview()->save($preview);

        dump($post);
        dump($post->preview->post);

        return "";
    });

    Route::get('/one-all', function () {
        $posts = Post::all();

        $posts->load('preview');


        // $preview = new PostPreview();
        // $preview->photo_url = 'qwe';

        // $post->preview()->save($preview);

        dump($posts);

        foreach ($posts as $post) {
            $post->preview;
        }

        return "";
    });

    Route::get("/poly", function () {
        Post::find(1);
    });
});

require __DIR__ . '/auth.php';
