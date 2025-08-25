<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    
    Route::middleware('auth:api')->get('/user',);

    Route::get('/polls', [PollController::class, 'index']);
    Route::get('/polls/{id}', [PollController::class, 'show']);
    Route::post('/polls', [PollController::class, 'store']);
    Route::delete('/polls/{id}', [PollController::class, 'destroy']);
    
    Route::post('/pollAnswers', [PollController::class, 'saveAnswers']);

    Route::get('/pollAnswers/{id}/createExcel', [PollController::class, 'downloadExcel']);
    Route::get('/pollAnswers/{id}/getGraph', [PollController::class, 'getChart']);
    
    Route::post('/login', [AuthController::class, 'login']);

});