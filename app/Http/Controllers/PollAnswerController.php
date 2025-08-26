<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PollAnswer;



class PollAnswerController extends Controller
{
    public function saveAnswers(Request $request) {

        $pollAnswersData = $request->all();
        
        foreach($pollAnswersData as $pollAnswers){
            foreach($pollAnswers as $answer){
                $answer['ip'] = $request->ip();
                $answer['self_comment'] = $answer['self_text'];
                
                PollAnswer::create($answer);
            }
        }
    }
}
