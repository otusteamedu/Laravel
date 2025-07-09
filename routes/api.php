<?php

use App\Http\Controllers\Api\V1\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;


Route::prefix('v1') ->as('v1.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
});

// Защищённые маршруты (требуют auth:api)
Route::middleware('auth:api')
     ->prefix('v1')
     ->as('v1.')
     ->group(function () {
         Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

         Route::apiResource('/news', NewsController::class);
     });
