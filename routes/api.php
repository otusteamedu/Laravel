<?php

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PollAnswerController;

use App\Models\Poll;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->get('/user', );

    Route::get('/polls', );

    Route::get('/polls/{pollId}', [PollController::class, 'getPoll']);

    Route::post('/polls', [PollController::class,'createPoll']);

    Route::put('/polls/{poll-id}', );

    Route::delete('/polls/{poll-id}',);

    Route::get('/polls/{poll-id}/answers', );

    Route::post('/pollAnswers', [PollAnswerController::class, 'saveAnswers']);

    
    // Route::post('/polls/answers', 
    // $postData = {
    //     "person_identifier" : "",
    //     "person_id" : "",
    //     "department_identifier" : "",
    //     "employee_id" : "",
    //     "department_id" : "",
    //     "poll_id": '',
    //     "poll_answers": [
    //         {
    //             "question_id": '',
    //             "answer_id": '',
    //             "comment": '',
    //             "self_comment": '',
    //         },
    //         {
    //             "question_id": '',
    //             "answer_id": '',
    //             "comment": '',
    //             "self_comment": '',
    //         },
    //     ]
    // }
        
    // );
});


//Route::resource('Polls',TestController::class);

// GET /api/v1/polls - get polls list
// GET /api/v1/polls/{poll-id} - get poll
// POST /api/v1/polls - create new poll
// PUT/PATCH /api/v1/polls/{poll-id} - update poll
// DELETE /api/v1/polls/{poll-id} - delete poll


// GET /api/v1/polls/{poll-id}/answers - create new poll answers
// POST /api/v1/polls/answers - create new poll answers

