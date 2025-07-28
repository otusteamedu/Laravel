<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// first commit

Route::get('/', function () {
    return redirect('/blogs');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('blogs', \App\Http\Controllers\BlogsController::class)->middleware('auth');

// Создадим пользователя с токеном11
Route::get('/user', function () {
    $user = new User;

    $user->name = 'olga';
    $user->email = 'olga123@mail.ru';
    $user->password = '123123123';
    $user->api_token = '12345';

    $user->save();

    dump($user->save());

    return '';
});

require __DIR__.'/auth.php';
