<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// first commit

/**
 * На странице /blogs будем выводить список блогов напрямую из БД. А на странице /cached - из кэша с ttl = 3600 секунд .
 * Отредактируем какой-нибудь пост. И увидим, что на странице /blogs данные обновились, а на странице /cached - нет.
 */
Route::resource('blogs', \App\Http\Controllers\BlogsController::class);

Route::get('/cached', function () {
    $newCache = Cache::get('blog', null);

    if ($newCache) {
        $blogs = $newCache;

        return view('blogs.index', compact('blogs'));
    } else {
        $blog = Blog::all();
        Cache::put('blog', $blog, 3600);
    }

    return '';

});
