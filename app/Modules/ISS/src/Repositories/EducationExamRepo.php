<?php


namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;

class EducationExamRepo implements EducationExamRepoInterface
{
    /**
     * Запрос БД извлеч все экзаменационные вопросы теста для данной точки обучающего маршрута
     * (вместе с вериантами ответов, если они есть)
     * @param array $inputData $inputData['id'] код реальной точки учебного маршрута
     * @return array ['rerp_id'=>, 'erp_id'=>, 'questionId'=>, 'questionName'=>, 'questionText'=>]
     */
    public function getExamQuestions(array $inputData): array
    {
        $result = [];

        //select rerp.id, erp.id, eq.id, eq.short_question_name, eq.question
        //from real_education_route_points rerp
        //join education_route_points erp on erp.id = rerp.route_point_id
        //join exam_questions eq on eq.point_id = erp.id
        //where rerp.id in(5,7,10)
        //
        try {
            $result = RealEducationRoutePoint::select(
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

        return $result;
    }

    /**
     * Запрос БД извлеч все ответы для экзаменационного вопроса теста
     * @param array $inputData $inputData['questionId'] код вопроса для контрольного теста
     * @return array ['id' => , 'answer' => ]
     */
    public function getExamAnswers(array $inputData): array
    {
        $qModel = ExamQuestion::where('id', $inputData['questionId'])->first();
        if (!is_null($qModel)) {
            return $qModel->examAnswer()->get(['id', 'answer'])->toArray();
        } else {
            return [];
        }
    }

    /**
     * Запрос БД найти количество сложных вопросов теста для данной реальной точки учебного маршрута
     * (вопрос считается сложным если у него нет вариантов ответа)
     * @param array $inputData $inputData['id'] код реальной точки маршрута
     * @return array ['countOfComplicatedQuestions' => ]
     */
    public function complicatedQuestionsCount(array $inputData): array
    {
        //select rerp.id, erp.id, eq.id, ea.id /*count(eq.id)*/
        //from real_education_route_points rerp
        //         join education_route_points erp on erp.id = rerp.route_point_id
        //         join exam_questions eq on eq.point_id = erp.id
        //         left join exam_answers ea on ea.question_id = eq.id
        //where rerp.id in(7) and ea.id is null

        return [
            'countOfComplicatedQuestions' => RealEducationRoutePoint::select('eq.id')
                ->join('education_route_points as erp', 'real_education_route_points.route_point_id', '=', 'erp.id')
                ->join('exam_questions as eq', 'erp.id','=', 'eq.point_id')
                ->join('exam_answers as ea', 'eq.id', '=', 'ea.question_id', 'left')
                ->where('real_education_route_points.id', $inputData['id'])->whereNull('ea.id')->count()
            ];
    }


}
