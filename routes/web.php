<?php

use App\Http\Controllers\RoutController;


Route::get('/{domain}/{method}/{essence}', [RoutController::class,'index'])
            ->name('index');
