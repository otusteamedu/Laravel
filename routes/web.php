<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => \App\Http\Middleware\SetLocale::class,
], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::resource('blogs', \App\Http\Controllers\BlogsController::class);

});

Route::get('/', function () {
    return redirect(app()->getLocale());
});
