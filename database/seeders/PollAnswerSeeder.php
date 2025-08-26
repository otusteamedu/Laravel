<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\PollAnswer;
use App\Models\Person;
use App\Models\Department;
use App\Models\Poll;

class PollAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $query = 'select * from poll_igor_results order by id';
        $igor_poll_results = DB::select($query);   
        foreach($igor_poll_results as $igor_poll_result){
            $pollId = Poll::where('poll_igor_id',$igor_poll_result->poll_id)->first()->id;
            $poll_body = json_decode($igor_poll_result->poll_body);
            $person = Person::find($igor_poll_result->fisical_id);
            // dd($poll_body);
            $department = Department::find($igor_poll_result->dep_id);
            foreach($poll_body as $question){
                $questionId = Question::where([
                    ['poll_id','=', $pollId],
                    ['order_number','=', $question->id]
                ])->first()->id;
                foreach($question->answers->variant_answers as $answer){
                    if($answer->value == 1){
                        $orderNumber = $question->question_types->isScale ? $answer->scaleValue : $answer->id;
                        $answerId = QuestionAnswer::where([
                            ['question_id','=', $questionId],
                            ['order_number','=', $orderNumber]
                        ])->first()->id;
                        $poll_result = [
                            'ip' => $igor_poll_result->user_ip,
                            'poll_id' => $pollId,
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
}
