<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome', [
        'services' => ['Услуга 1', 'Услуга 2', 'Услуга 3']
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/about', 'about');


Route::get('/log', function () {
    //Log::debug('debug');
    /*Log::channel('telegram')->debug('debug level error');
    Log::channel('telegram')->info('info level error');
    Log::channel('telegram')->error('error level error');
    Log::channel('telegram')->critical('critical level error');
    Log::channel('telegram')->emergency('emergency level error');*/
    //Log::channel('telegram')->critical('critical level error');

    return ['status' => 'success'];
});
