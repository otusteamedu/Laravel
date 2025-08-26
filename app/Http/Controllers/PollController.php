<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use App\Models\Question;
use App\Models\QuestionAnswer;


class PollController extends Controller
{
    public function getPoll($pollId) {
        //$poll = Poll::find($pollId);
        $poll = Poll::where('poll_igor_id',$pollId)->first();
        return $poll ? new PollResource($poll) : null;
    }


    public function createPoll(Request $request) {
        $params = $request->all();
        $poll = new Poll($params);
        $poll->poll_igor_id = rand(1,10000);
        $poll->save();
        foreach($params['questions'] as $question){
            $question['poll_id'] = $poll->id;
            $questionId = Question::create($question)->id;
            foreach($question['answers'] as $answer){
                $answer['poll_id'] = $poll->id;
                $answer['question_id'] = $questionId;
                QuestionAnswer::create($answer);
            }
        }
        //$poll->save();
        
        dd($poll);
    }
}
