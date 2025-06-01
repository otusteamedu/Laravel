<?php

namespace App\Modules\ISS\src\Services\EducationExam;

use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use Illuminate\Database\QueryException;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;

class EducationExamService
{
    /**
     * Достать все экзаменационные вопросы теста для данной точки обучающего маршрута (вместе с вериантами ответов, если они есть)
     * @param array $inputData
     * @return array
     */
    /*public function getExamQuestions(array $inputData): array
    {
        $result = [];

        //select rerp.id, erp.id, eq.id, eq.short_question_name, eq.question
        //from real_education_route_points rerp
        //join education_route_points erp on erp.id = rerp.route_point_id
        //join exam_questions eq on eq.point_id = erp.id
        //where rerp.id in(5,7,10)
        //
        try {
            $questions = RealEducationRoutePoint::select(
                'real_education_route_points.id as rerp_id',
                'erp.id as erp_id',
                'eq.id as questionId',
                'eq.short_question_name as questionName',
                'eq.question as questionText'
                //,'ea.id as ea_id',
                //,'ea.answer as answer'
            )->join('education_route_points as erp', 'real_education_route_points.route_point_id', '=', 'erp.id')
                ->join('exam_questions as eq', 'erp.id', '=', 'eq.point_id')
                //->join('exam_answers as ea', 'eq.id', '=', 'ea.question_id', 'left')
                ->where('real_education_route_points.id', $inputData['id'])->get()->toArray();
        } catch (\Error | \Exception $e) {
            $result = [];
        }

        for ($i=0; $i < count($questions); $i++) {
            $qModel = ExamQuestion::where('id', $questions[$i]['questionId'])->first();
            if (!is_null($qModel)) {
                $questions[$i]['answers'] = $qModel->examAnswer()->get(['id', 'answer'])->toArray();
            }
        }

        return $result;
    }*/

    /**
     * Определение типа теста (простой или сложный)
     * @param array $inputData код реальной точки маршрута
     * @return bool (false -- простой тест \ true -- сложный тест)
     */
    /*public function isExamComplicated(array $inputData): bool
    {
        //
        //select count(eq.id) -- rerp.id, erp.id, eq.id, ea.id
        //from real_education_route_points rerp
        //join education_route_points erp on erp.id = rerp.route_point_id
        //join exam_questions eq on eq.point_id = erp.id
        //left join exam_answers ea on ea.question_id = eq.id
        //where rerp.id in(5) and ea.id is null

        $countOfComplicatedQuestions = 0;

        $countOfComplicatedQuestions = RealEducationRoutePoint::select('eq.id')
            ->join('education_route_points as erp', 'real_education_route_points.route_point_id', '=', 'erp.id')
            ->join('exam_questions as eq', 'erp.id','=', 'eq.point_id')
            ->join('exam_answers as ea', 'eq.id', '=', 'ea.question_id', 'left')
            ->where('real_education_route_points.id', $inputData['id'])->count();

        if ($countOfComplicatedQuestions > 0) {
            return true;
        } else {
            return false;
        }
    }*/


    /**
     * Опеделить вид проверки теста (автоматическая или отправкой преподавателю)
     * @param array $inputData код реальной точки учебного маршрута
     * @return string
     */
    /*public function chooseCheckType(array $inputData): string
    {
        $result = null;
        //exam_questions + education_route_points + real_education_route_pints
        //если все вопросы simple то авто, если хоть один не simple то преподу

        if ($this->isExamComplicated($inputData)) {
            $result = 'manual';
        } else {
            $result = 'auto';
        }

        return $result;
    }*/

    /**
     * Проверить простой тест (только варианты ответа)
     * @param array $inputData запрос из формы, в т.ч. код реальной точки учебного маршрута
     * @return string
     */
    public function checkSimpleTest(array $inputData): string
    {
        $result = null;
        //exam_questions + exam_answers
        return $result;
    }

    /**
     * Отметить что тест для точки учебного маршрута сдан (точка пройдена)
     * @param array $inputData код реальной точки маршрута, код пользователя ИОС сдавшего тест
     * @return string
     */
    public function markRealRoutePointExamPassed(array $inputData): string
    {
        $result = null;
        //real_education_route_points + real_education_routes_of_users
        //генерация события что тест пройден
        return $result;
    }

    /**
     * Передать тест на проверку преподавателю
     * @param array $inputData данные из формы запроса, код реальной точки маршрута, код пользователя ИОС сдавшего тест
     * @return string
     */
    public function sendExamResultToExaminer(array $inputData): string
    {
        $result = null;
        //real_education_route_points + real_education_routes_of_users
        //генерируем событие тест передан на проверку
        return $result;
    }
}
