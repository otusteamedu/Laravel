<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::group(['middleware' => 'auth:api',
    'headers' => ['Accept' => 'application/json', 'title' => '55555'],
], function () {
    Route::get('index', function () {
        return Blog::all();
    });

    Route::get('index/{id}', function () {
        return Blog::find($this->id);

    });
});

Route::apiResource('posts', \App\Http\Controllers\PostsController::class);
