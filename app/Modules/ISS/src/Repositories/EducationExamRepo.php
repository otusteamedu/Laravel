<?php


namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\ExamAnswer;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\src\Models\Teacher;
use App\Modules\ISS\src\Models\ExamCheckCode;

class EducationExamRepo implements EducationExamRepoInterface
{
    /**
     * Запрос БД извлеч все экзаменационные вопросы теста для данной точки обучающего маршрута
     * (вместе с вериантами ответов, если они есть)
     * @param array $inputData $inputData['id'] код реальной точки учебного маршрута
     * @return array
     *              [
     *              ['rerp_id'=>, 'erp_id'=>, 'questionId'=>, 'questionName'=>, 'questionText'=>],
     *              ['rerp_id'=>, 'erp_id'=>, 'questionId'=>, 'questionName'=>, 'questionText'=>],
     *              ...
     *              ]
     */
    public function getExamQuestions(array $inputData): array
    {
        //select rerp.id, erp.id, eq.id, eq.short_question_name, eq.question
        //from real_education_route_points rerp
        //join education_route_points erp on erp.id = rerp.route_point_id
        //join exam_questions eq on eq.point_id = erp.id
        //where rerp.id in(5,7,10)
        //

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

        return $result;
    }

    /**
     * Запрос БД извлеч все ответы для экзаменационного вопроса теста
     * @param array $inputData $inputData['question_id'] код вопроса для контрольного теста
     * @return array
     *              [
     *                ['id' => , 'answer' => ],
     *                ['id' => , 'answer' => ],
     *                ['id' => , 'answer' => ],
     *                ...
     *              ]
     */
    public function getExamAnswers(array $inputData): array
    {
        $qModel = ExamQuestion::where('id', $inputData['question_id'])->first();
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

    /**
     * Запрос БД выбранные экзаменационные вопросы с ответами
     * @param array $inputData
     *              коды вопросов для которых ищем верные ответы
     *                  $inputData['question1_id', 'question2_id', ....]
     * @return array [
     *                ['id'=>, 'question'=>, ... ,'exam_answer'=> [ ['id'=>, 'answer'=>, 'is_right'=>, ...], [...], .... ],
     *                ['id'=>, 'question'=>, ... ,'exam_answer'=> [ ['id'=>, 'answer'=>, 'is_right'=>, ...], [...], .... ],
     *                ...
     *               ]
     */
    public function getQuestionsWithAnswers(array $inputData): array
    {
        return ExamQuestion::with('examAnswer')->whereIn('id', $inputData)->get()->toArray();
    }

    /**
     * Запрос БД найти id реального маршрута пользователя, которому пренадлежит заданная реальная точка маршрута
     * (для заданного пользователя)
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     *              код реальной точки маршрута
     *                  $inputData['id']
     * @return array ['reru_id'=> ]
     */
    public function getRealRouteIdForRealPointBelongs(array $inputData): array
    {
        //    select t1.id
        //    -- t.id as rerp, t.route_id er, t1.id reru, t1.last_pass_point_id
        //    from real_education_route_points t
        //    left join real_education_routes_of_users t1 on t1.route_id = t.route_id
        //    where t.id = 3        -- real_education_route_point_id
        //  and t1.user_data_id = 2 -- iss_user_id

        return RealEducationRoutePoint::select('reru.id as reru_id')
            ->join(
                'real_education_routes_of_users as reru',
                'real_education_route_points.route_id',
                '=',
                'reru.route_id',
                'left'
            )->where('real_education_route_points.id' , $inputData['id'])
            ->where('reru.user_data_id', $inputData['iss_user_id'])->first()->toArray();
    }

    /**
     * Запрос БД обновить значение пройденной точки маршрута для заданного пользователя и его реального маршрута
     * @param array $inputData
     *               код реальной точки маршрута (которую заносим в real_education_route_of_users.last_pass_point)
     *                   $inputData['point_id']
     *               код реального маршрута полользователя который обновляем
     *                   $inputData['id']
     * @return bool
     */
    public function updateLastPassPoint(array $inputData): bool
    {
        $realRoute = RealEducationRoutesOfUser::find($inputData['id']);
        $realRoute->last_pass_point_id = $inputData['point_id'];

        return $realRoute->save();
    }

    /**
     * Запрос БД выбрать адреса почты всех преподавателей, относящихся к заданной организации
     * @param array $inputData
     *              название организации
     *                  $inputData['organization']
     * @return array [ ['teacher_email' => 'mail1'], ['teacher_email' => 'mail2'], ..... ]
     */
    public function getTeachersOfOrganization(array $inputData): array
    {
        return Teacher::select('teacher_email')
            ->where('connected_organization', $inputData['organization'])->get()->toArray();
    }

    /**
     * Запрос БД найти код пользователя ИОС (сдавашего экзамен)
     * и код реальной точки обучающего маршрута (для которой сдвали экзамен) по одноразовому коду проверки у преподавателя
     * @param array $inputData
     *               одноразовый код проверки, переданный преподавателю
     *                   $inputData['exam_check_code']
     * @return array ['iss_user_id', 'real_route_point_id']
     */
    public function getUserAndPointDataByCheckCode(array $inputData): array
    {
        return ExamCheckCode::select(['iss_user_id', 'real_route_point_id'])
            ->where('exam_check_code', $inputData['exam_check_code'])->first()->toArray();
    }


    /**
     * Запрос БД найти одноразовый код проверки преподавателя по кодам
     * пользователя ИОС (сдавашего экзамен)
     * и коду реальной точки обучающего маршрута (для которой сдвали экзамен)
     * @param array $inputData
     *               код польз-я ИОС
     *                   $inputData['iss_user_id']
     *               код реальной точки маршрута
     *                   $inputData['real_route_point_id']
     * @return array ['exam_check_code' => ]
     */
    public function getCheckCodeByUserIdAndRealPointId(array $inputData): array
    {
        return ExamCheckCode::select(['exam_check_code'])
            ->where('iss_user_id', $inputData['iss_user_id'])
            ->where('real_route_point_id', $inputData['real_route_point_id'])
            ->first()->toArray();
    }

    /**
     * Запрос БД удалить запись из таблицы для заданного проверочного кода
     * @param array $inputData
     *               одноразовый код проверки, переданный преподавателю
     *                   $inputData['exam_check_code']
     *               флаг что требуется мягкое удаление
     *                   $inputData['soft_delete']
     * @return bool
     */
    public function delCheckCode(array $inputData): bool
    {
        $target = ExamCheckCode::where('exam_check_code', '=', $inputData['exam_check_code'])->first();
        if ($inputData['soft_delete']) {
            return $target->delete();
        } else {
            return $target->forceDelete();
        }
    }

    /**
     * Запрос БД создать запись в таблице для заданного проверочного кода
     * @param array $inputData
     *               код пользователя ИОС сдающего экзамен
     *                   $inputData['iss_user_id']
     *               код реальной точки маршрута
     *                   $inputData['real_route_point_id']
     *               одноразовый код для проверки экзамена
     *                   $inputData['exam_check_code']
     * @return bool
     */
    public function makeCheckCode(array $inputData): bool
    {
        $result = ExamCheckCode::make(
            [
                'iss_user_id' => $inputData['iss_user_id'],
                'real_route_point_id' => $inputData['real_route_point_id'],
                'exam_check_code' => $inputData['exam_check_code']
            ]
        );
        return $result->save();
    }


    /**
     * Запрос БД получить код маршрута (из справочника) по коду реальной точки маршрута
     * @param array $inputData
     *               код реальной точки маршрута
     *                   $inputData['id']
     * @return array ['route_id' => ]
     */
    public function getRealRouteIdByRealPointId(array $inputData): array
    {
        return RealEducationRoutePoint::select('route_id')->where('id', $inputData['id'])->first()->toArray();
    }

    /**
     * Запрос БД получить код последней пройденной точки маршрута пользователя
     * @param array $inputData
     *               код маршрута из справочника
     *                   $inputData['route_id']
     *               код пользователя ИОС
     *                   $inputData['user_data_id']
     * @return array ['lpp_id' => ]
     */
    public function getLPPid(array $inputData): array
    {
        return RealEducationRoutesOfUser::select('last_pass_point_id as lpp_id')
            ->where('route_id', $inputData['route_id'])
            ->where('user_data_id', $inputData['user_data_id'])->first()->toArray();
    }

    /**
     * Запрос БД получить код первой (по порядку) точки на обучающем мершруте
     * @param array $inputData
     *               код маршрута пользователя
     *                   $inputData['route_id']
     * @return array ['id' => ]
     */
    public function getFirstRoutePoint(array $inputData): array
    {
        return RealEducationRoutePoint::select('id')
            ->where('route_id', $inputData['route_id'])->orderBy('position', 'asc')->first()->toArray();
    }

    /**
     * Запрос БД получить значение позиции для последней пройденной точки реального обучающего маршрута
     * @param array $inputData
     *               код реальной точки маршрута
     *                   $inputData['id']
     * @return array ['position' => ]
     */
    public function getLPPposition(array $inputData): array
    {
        return RealEducationRoutePoint::select('position')->where('id', $inputData['id'])->first()->toArray();
    }

    /**
     * Запрос БД получить код реальной точки маршрута, следующей за последней пройденной точкой
     * @param array $inputData
     *               позиция на маршруте последней пройденной точки
     *                   $inputData['position']
     *               код маршрута из справочника
     *                    $inputData['route_id']
     * @return array ['id' => ]
     */
    public function getNextExamPoint(array $inputData): array
    {
        return RealEducationRoutePoint::select('id')
            ->where('position', '>', $inputData['position'])
            ->where('route_id', $inputData['route_id'])
            ->orderBy('position', 'asc')->limit(1)->get()->toArray();
    }
}
