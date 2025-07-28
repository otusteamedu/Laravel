<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// Токен для аутентификации
$token = '12345';

Route::group(['middleware' => 'auth:api', 'parameters' => ['token' => $token],
], function () {
    Route::get('create', function () {
        $blog = new Blog;

        $blog->title = 'New title 123123';
        $blog->text = 'New text new text';
        $blog->preview = 'New preview new preview';
        $blog->author_id = 2;

        $blog->save();

        return dump($blog->save());

    });

    Route::get('update/{id}', function (string $id) {
        $blog = Blog::find($id);

        $blog->title = 'Updated title==========================';
        $blog->save();

        return dump($blog->save());

    });

    Route::get('delete/{id}', function (string $id) {
        $blog = Blog::find($id);

        return dump($blog->delete());

    });

    Route::get('index', function () {
        return Blog::all();
    });

    Route::get('index/{id}', function (string $id) {
        return Blog::find($id);

    });
});
