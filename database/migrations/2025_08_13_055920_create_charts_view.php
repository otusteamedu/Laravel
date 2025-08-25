<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = '
            create view charts as (
                select
                    p.id poll_id,
                    q.id question_id,
                    qa.id answer_id,
                    p.number poll_number,
                    p.name poll_name,
                    qt.id question_type_id,
                    qt.name question_type_name,
                    qct.id question_chart_type_id,
                    qct.name question_chart_type_name,
                    q.min_answer_count min,
                    q.max_answer_count max,
                    q.name_for_chart question_name,
                    qa.name_for_chart answer_name,
                    q.order_number question_number,
                    qa.order_number answer_number,
                    (
                        select count(pa.id) from poll_answers pa where pa.question_id = q.id and pa.answer_id = qa.id
                    ) answer_count,
                    (
                        select count(distinct pa.identifier) from poll_answers pa where poll_id = p.id
                    ) poll_count
                from
                    questions q
                join question_answers qa on qa.question_id = q.id
                join polls p on p.id = q.poll_id 
                join question_types qt on qt.id = q.type_id
                join question_chart_types qct on qct.id = q.chart_type_id              
            )
        ';
        DB::statement($query);
        $query = "comment on view charts is 'Представление объекта «График»'";
        DB::statement($query);
        $fields = [
            'poll_id' => '1',
            'question_id' =>  '2',
            'answer_id' =>  '3',
            'poll_number' =>  '4',
            'poll_name' =>  '5',
            'question_type_id' => '7',
            'question_type_name' => '7',
            'question_chart_type_id' => '7',
            'question_chart_type_name' => '7',
            'min' => '7',
            'max' => '7',
            'question_name' => '',
            'answer_name' => '',
            'question_number' => '',
            'answer_number' => '',
            'answer_count' => '',
            'poll_count' => ''
        ];
        foreach($fields as $fieldName => $comment){
            $query = "comment on column charts.$fieldName is '$comment'";
            DB::statement($query);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drop view charts');
    }
};
