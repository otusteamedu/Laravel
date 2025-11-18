<?php

use App\DbQueries\PostTableQueries;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\User;
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

Route::get('/csrf', function () {
    return csrf_token();
})->name('csrf');

Route::view('/route_name', 'route');

Route::post('/test', function () {
    return "test";
});

Route::resource('posts', PostController::class);

Route::get('/google', fn() => redirect()->route('csrf', []));

Route::group(['prefix' => '/qwe', 'as' => 'qwe.', 'middleware' => ['auth']], function ($route) {
    Route::get('/calc/{a}/{b}/{prefix?}/{suffix?}', function (Request $request, int $x1, int $x2) {

        $prefix = request()->route('prefix', 'default');
        $suffix = request()->route('suffix', 'default');
        dump($x1);
        dump($x2);
        dump($x1 + $x2);

        return $prefix . ' = ' . $x1 + $x2 . " $suffix";
    })->whereNumber('a')->whereNumber('b')->name('calc');
});

Route::get('/posts/by_author/{author}/{post}', function (User $author, Post $post) {
    dump($author);
    dump($post);
    return '';
})->scopeBindings()->missing(function () {
    return response('not found');
});

Route::fallback(function () {
    return '404';
});

require __DIR__ . '/auth.php';

