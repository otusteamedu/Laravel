<?php

namespace ISS\App\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Infrastructure\Models\ExamQuestion;
use ISS\App\Infrastructure\Models\ExamAnswer;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;
use ISS\App\Infrastructure\Models\RealEducationRoutesOfUser;
use ISS\App\Infrastructure\Models\Teacher;
use ISS\App\Infrastructure\Models\ExamCheckCode;

class EducationExamRepo implements EducationExamRepoInterface
{
    /**
     * Запрос БД извлечь экзаменационные вопросы для СПРАВОЧНОЙ точки экзаменационного маршрута
     *  (вместе с вериантами ответов, если они есть)
     * @param array $inputData $inputData['id'] код справочной точки учебного маршрута
     * @return array
     *               [
     *                 ['id'=>, 'question'=>, ... ,'exam_answer'=> [ ['id'=>, 'answer'=>, 'is_right'=>, ...], [...], .... ],
     *                 ['id'=>, 'question'=>, ... ,'exam_answer'=> [ ['id'=>, 'answer'=>, 'is_right'=>, ...], [...], .... ],
     *               ...
     *               ]
     */
    public function getRefPointQuestions(array $inputData): array //тоже можно в scope модели
    {
        return ExamQuestion::with('examAnswer')->where('point_id', $inputData['id'])->get()->toArray();
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
    public function getQuestionsWithAnswers(array $inputData): array //это просится в scope модели
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
     * Запрос БД получить тексты ответов на экзаменационные вопросы по переданному массиву их id
     * @param array $inputData
     *        ['id1', 'id2', 'id3', ....]
     * @return array [
     *                 ['id' => , 'answer' => ],
     *                 ['id' => , 'answer' => ],
     *                 ['id' => , 'answer' => ],
     *                 ...
     *                ]
     */
    public function getAnswersText(array $inputData): array
    {
        return ExamAnswer::select(['id', 'answer'])->whereIn('id', $inputData)->get()->toArray();
    }

    /**
     * Запрос БД получить тексты экзаменационных вопросов по переданному массиву их id
     * @param array $inputData
     *        ['id1', 'id2', 'id3', ....]
     * @return array [
     *                 ['id' => , 'question' => ],
     *                 ['id' => , 'question' => ],
     *                 ['id' => , 'question' => ],
     *                 ...
     *                ]
     */
    public function getQuestionsText(array $inputData): array
    {
        return ExamQuestion::select(['id', 'question'])->whereIn('id', $inputData)->get()->toArray();
    }
}
