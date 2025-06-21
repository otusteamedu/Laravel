<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use PshenichkinMaxim\UserLogo\Facades\UserLogo;

Route::get('/', function () {
    return view('welcome', [
        'services' => ['Услуга 1', 'Услуга 2', 'Услуга 3']
    ]);
});

Auth::routes();

Route::get('/home', HomeController::class)->middleware(['auth'])->name('home');
Route::view('/about', 'about');

Route::get('/test-package', function (UserLogo $userLogo) {

   // dd(UserLogo::generate('Иванов Иван'));
    //dd(userlogo('Иванов Иван'));
    return '';
});
