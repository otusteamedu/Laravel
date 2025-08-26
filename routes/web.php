<?php

use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/home', HomeController::class)->middleware(['auth'])->name('home');

require('admin.php');
