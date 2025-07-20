<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::apiResource('posts', \App\Http\Controllers\PostsController::class);

Route::get('/user', function (Request $request) {
    dump($request);

    return $request->user();
});
