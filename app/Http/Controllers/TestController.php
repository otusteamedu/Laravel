<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Poll;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\PollAnswer;
use App\Models\Person;
use App\Models\Department;

use App\Http\Resources\PollResource;

class TestController extends Controller
{

    public function seed__(){
        //return PollResource::collection(Poll::paginate(2));
        //return new PollResource(Poll::find(7037));
        
    }

    public function seed(){
        $query = 'truncate poll_answers';
        DB::statement($query);
        $query = 'select * from poll_igor_results order by id';
        $igor_poll_results = DB::select($query);   
        foreach($igor_poll_results as $igor_poll_result){
            $poll_body = json_decode($igor_poll_result->poll_body);
            $person = Person::find($igor_poll_result->fisical_id);
            // dd($poll_body);
            $department = Department::find($igor_poll_result->dep_id);
            foreach($poll_body as $question){
                $questionId = Question::where([
                    ['poll_id','=', $igor_poll_result->poll_id],
                    ['order_number','=', $question->id]
                ])->first()->id;
                foreach($question->answers->variant_answers as $answer){
                    if($answer->value == 1){
                        $answerId = QuestionAnswer::where([
                            ['question_id','=', $questionId],
                            ['order_number','=', $answer->id]
                        ])->first()->id;
                        $poll_result = [
                            'ip' => $igor_poll_result->user_ip,
                            'poll_id' => $igor_poll_result->poll_id,
                            'question_id' => $questionId,
                            'answer_id' => $answerId,
                            'comment' => property_exists($question->answers, 'commentText') ? $question->answers->commentText : null,
                            'self_comment' => property_exists($question->answers, 'self_answer') ? $question->answers->self_answer->text : null,
                            'person_identifier' => $person ? $person->identifier : null,
                            'person_id' => $person ? $person->id : null,
                            'department_identifier' => $department ? $department->identifier : null,
                            //'employee_id' => '',
                            'department_id' => $department ? $department->id : null,
                            'created_at' => $igor_poll_result->created_at
                        ];
                        PollAnswer::create($poll_result);
                        
                    }


                }
                
            }

        }

        
    }

    public function seed_poll(){
        $query = 'truncate question_answers cascade';
        DB::statement($query);
        $query = 'truncate questions cascade';
        DB::statement($query);
        $query = 'truncate polls cascade';
        DB::statement($query);

        $query = 'select * from poll_igor order by id';
        $igor_polls = DB::select($query);
        foreach($igor_polls as $igor_poll){
            $body = json_decode(trim(str_replace('\\"','"',$igor_poll->body),'"'));
            //dd($body);
            //$body = json_decode();
            $authorized = ($body->body->anonym == 0) ? true : false;
            
            if($body->body->noGraphForever == 1){
                $chartMode = 0;
            }else{
                $chartMode = $body->body->isGraph == 0 ? 1 : 2;
            }
            $poll = [
                'id' => $igor_poll->id,
                'name' => $igor_poll->name,
                'description' => $body->title->poll_name,
                'start_text' => $body->body->start_text,
                'end_text' => $body->body->finish_text->texts[0]->text,
                'authorized' => $authorized,
                'chart_mode' => $chartMode,
                'start_date' => $igor_poll->created_at,
                'end_date' => $igor_poll->updated_at,
            ];
            Poll::create($poll);

            foreach($body->questions as $body_question){
                $comment = $body_question->question_types->isComment == 0 ? false : true;
                $type_id = 1;
                // if ($body_question->question_types->single == 1) {
                //     $type_id = 1;
                // }
                if ($body_question->question_types->multiply == 1) {
                    $type_id = 2;
                }

                if ($body_question->question_types->isScale == 1) {
                    $type_id = 3;
                }
                $question = [
                    'poll_id' => $igor_poll->id,
                    'order_number' => $body_question->id,
                    'required' => $body_question->question_types->isRequired,
                    'name' => $body_question->text,
                    'type_id' => $type_id,
                    'icon' => '',
                    'comment' => $comment
                ];
                // $self =  $body_question->question_types->self == 1 ? true : false;
                
                // $answerSelf = [
                //     'self'=> $self,
                // ];

                
                $questionId = Question::create($question)->id;
                // dd($questionId);
                $selected = count($body_question->answers->variant_answers) == 1 ? true : false;
                $self = $body_question->question_types->self == 1 ? true : false;
                foreach($body_question->answers->variant_answers as $body_question_answer){
                    $excluded_order_numbers = null;
                    
                    if($body_question_answer->rules){
                        $excluded_order_numbers = explode(',',$body_question_answer->rules);
                    }
                    
                    $answer = [
                        'question_id'=> $questionId,
                        'order_number'=> $body_question_answer->id,
                        'name'=> $body_question_answer->text,
                        'self'=> $self,
                        'selected'=> $selected,
                        'icon'=> $body_question_answer->icon,
                        'excluded_order_numbers'=> $excluded_order_numbers,
                    ];
                    
                    echo $igor_poll->id.' => '.$body_question->id.' => '.$body_question_answer->id.'<br>';
                    QuestionAnswer::updateOrCreate($answer);
                    
                }
                // QuestionAnswer::create($answerSelf);
                //dd($question);
            }

            // foreach($body->questions->answers->variant_answers as $body_question_answer){
            //     $selected = count($body->questions->answers->variant_answers) == 1 ? true : false;
            //     // $self = $body->questions->question_types->self == 1 ? true : false;
            //     $answer = [
            //         'question_id'=> $body_question_answer->id,
            //         'order_number'=> $body_question_answer->id,
            //         'name'=> $body_question_answer->text,
            //         // 'self'=> $self,
            //         'selected'=> $selected,
            //         'icon'=> $body_question_answer->icon,
            //         'excluded_order_numbers'=> $body_question_answer->rules,
            //     ];

            //     QuestionAnswer::updateOrCreate($answer);
            // }
            
        }

        dd(123);






    }
}
