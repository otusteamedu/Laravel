<?php

use App\DbQueries\PostTableQueries;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Repositories\PostRepo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::view('/main', '/pages/hello');

Route::get('/posts', function () {
    $posts = Post::with('author')->get();

    $posts->map(fn($post) => dump($post->author->name));
    dump($posts);
    return '';
});

Route::get('/posts/{post}', function (Post $post) {
    dump($post->author);
    dump($post->tags);
    dump($post);
    return '';
});

Route::get('/posts/create', function () {
    // $post = new Post();
    // $post->title = 'created post';
    // $post->text = 'text post';
    // $post->user_id = 1;

    // $post->save();

    Post::create([
        'title' => 'created post',
        'text' => 'text post',
        'user_id' => 1
    ]);

    return 'ok';
});

Route::get('/posts/edit/{post}', function (Post $post) {
    $post->text = 'edited';
    $post->save();

    return "edited";
});

Route::get('/posts/delete/{post}', function ($postId) {
    $post = Post::find($postId);
    if ($post) {
        $post->delete();
        return "deleted";
    } else {
        return "already deleted";
    }
});

Route::get('/posts/force-delete/{post}', function ($postId) {
    $post = Post::find($postId);
    if ($post) {
        $post->forceDelete();
        return "deleted";
    } else {
        return "already deleted";
    }
});

Route::get('/posts/restore/{post}', function ($postId) {
    $post = Post::withTrashed()->find($postId);
    dump($post);
    $post->restore();
    return '';
});

require __DIR__ . '/auth.php';
