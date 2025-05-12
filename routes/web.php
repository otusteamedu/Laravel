<?php

use App\Http\Controllers\ProfileController;
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

require __DIR__ . '/auth.php';
