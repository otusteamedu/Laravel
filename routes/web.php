<?php

use Illuminate\Support\Facades\Route;

// Route::resource('blogs', \App\Http\Controllers\BlogsController::class);

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => \App\Http\Middleware\SetLocale::class,
], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('blogs', \App\Http\Controllers\BlogsController::class);

});

Route::get('/', function () {
    return redirect(app()->getLocale());
});
