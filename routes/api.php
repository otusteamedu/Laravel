<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::apiResource('posts', \App\Http\Controllers\PostsController::class);
