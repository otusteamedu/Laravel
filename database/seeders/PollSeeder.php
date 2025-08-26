<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Poll;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionType;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $query = 'select * from poll_igor order by id';
        $igor_polls = DB::select($query);
        foreach($igor_polls as $igor_poll){
            $body = json_decode(trim(str_replace('\\"','"',$igor_poll->body),'"'));

            $authorized = ($body->body->anonym == 0) ? true : false;
            
            if($body->body->noGraphForever == 1){
                $chartMode = 0;
            }else{
                $chartMode = $body->body->isGraph == 0 ? 1 : 2;
            }
            $poll = [
                'poll_igor_id' => $igor_poll->id,
                'name' => $igor_poll->name,
                'description' => $body->title->poll_name,
                'start_text' => $body->body->start_text,
                'end_text' => $body->body->finish_text->texts[0]->text,
                'authorized' => $authorized,
                'chart_mode' => $chartMode,
                'start_date' => $igor_poll->created_at,
                'end_date' => $igor_poll->updated_at,
            ];
            $pollId = Poll::create($poll)->id;

            foreach($body->questions as $body_question){
                $comment = $body_question->question_types->isComment == 0 ? false : true;
                $type_id = QuestionType::where('name','single')->first()->id;
                if ($body_question->question_types->multiply == 1) {
                    $type_id = QuestionType::where('name','multi')->first()->id;
                }

                if ($body_question->question_types->isScale == 1) {
                    $type_id = QuestionType::where('name','scale')->first()->id;
                }
                $question = [
                    'poll_id' => $pollId,
                    'order_number' => $body_question->id,
                    'required' => $body_question->question_types->isRequired,
                    'name' => $body_question->text,
                    'name_for_chart' => $body_question->text,
                    'type_id' => $type_id,
                    'min_answer_count' => $body_question->question_types->min ? $body_question->question_types->min : null,
                    'max_answer_count' => $body_question->question_types->max ? $body_question->question_types->max : null,
                    'icon' => '',
                    'comment' => $comment
                ];
                $questionId = Question::create($question)->id;
                if($body_question->question_types->isScale == 1){
                    foreach($body_question->question_types->scaleNames as $order_number => $answerName){
                        $order_number++;
                        $answer = [
                            'poll_id' => $pollId,
                            'question_id'=> $questionId,
                            'order_number'=> $order_number,
                            'name'=> $answerName,
                            'name_for_chart'=> $answerName,
                            'self'=> false,
                            'selected'=> false,
                            'icon'=> null,
                            'excluded_order_numbers'=> null
                        ];
                        QuestionAnswer::updateOrCreate($answer);
                    }
                }else{
                    $selected = count($body_question->answers->variant_answers) == 1 ? true : false;
                    $self = $body_question->question_types->self == 1 ? true : false;
                    foreach($body_question->answers->variant_answers as $body_question_answer){
                        $answer = [
                            'poll_id' => $pollId,
                            'question_id'=> $questionId,
                            'order_number'=> $body_question_answer->id,
                            'name'=> $body_question_answer->text,
                            'name_for_chart'=> $body_question_answer->text,
                            'self'=> $self && count($body_question->answers->variant_answers) == $body_question_answer->id ? true : false,
                            'selected'=> $selected,
                            'icon'=> $body_question_answer->icon,
                            'excluded_order_numbers'=> $body_question_answer->rules ? explode(',',$body_question_answer->rules) : null,
                        ];
                        QuestionAnswer::updateOrCreate($answer);
                    }
                }
            }
        }
    }
}
