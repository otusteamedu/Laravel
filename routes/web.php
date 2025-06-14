<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FallbackController;

Auth::routes();

Route::prefix('{locale}')
    ->where(['locale' => implode('|', config('app.supported_locales'))])
    ->middleware(['locale'])
    ->group(function () {
        Route::get('/', function () {
            return view('welcome', [
                'services' => ['Услуга 1', 'Услуга 2', 'Услуга 3']
            ]);
        });

        Route::get('/home', HomeController::class)->middleware(['auth'])->name('home');
    }
);

Route::fallback(FallbackController::class);
