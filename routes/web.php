<?php

use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\PostPreview;
use App\Queries\UserQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/page', 'page');

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
