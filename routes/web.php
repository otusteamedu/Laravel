<?php

use App\Http\Controllers\InvokableController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\returnArgument;

Route::get('/', function () {
    $cacheSuffix = Auth::user() ? 'user' : 'anon';

    return Cache::rememberForever('view_welcome:' . $cacheSuffix, fn() => view('welcome')->render());
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('posts.comments', PostCommentController::class);
});

Route::get('/invokable', InvokableController::class);

Route::post('/posts/{post}/like', [PostLikeController::class, 'likePost'])->name('posts.like');
Route::post('/posts/{post}/unlike', [PostLikeController::class, 'unlikePost'])->name('posts.unlike');

Route::get('/login_as/{user}', function (Request $request, User $user) {
    Auth::login($user);
    return "ok";
});

Route::get('/user', function () {
    dump(Auth::check());
});

Route::get('/a/basic', function () {
    return "protected";
})->middleware('auth.basic');

Route::get('/a/by_email/{email}', function () {
    dump(Auth::user());
    return 'by_email';
})->middleware('auth:email');

Route::get('/c/get', function () {
    $name = Cache::tags(['name', 'user'])->get('name', 'anon');

    dump($name);

    return $name;
});

Route::get('/c/set', function () {
    $name = Cache::tags(['name', 'user'])->set('name', 'John');

    dump($name);

    return $name;
});

Route::get('/lock', function () {
    $lock = Cache::lock('qwe', seconds: 10);

    dump($lock);
    try {
        return $lock->block(3, function () {
            sleep(4);
            return 'done';
        });
    } catch (LockTimeoutException $e) {
        return 'locked';
    }
});

Route::get('/ascii', fn() => "from framework");

require __DIR__ . '/auth.php';
