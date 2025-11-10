<?php

use App\DbQueries\PostTableQueries;
use App\Http\Controllers\ProfileController;
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

Route::get('/posts', function (PostRepo $postRepo) {
    $posts = $postRepo->getTrickyPosts();
    dump($posts);
    return '';
});

require __DIR__ . '/auth.php';
