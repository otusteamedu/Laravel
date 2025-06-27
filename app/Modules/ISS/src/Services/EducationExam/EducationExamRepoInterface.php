<?php

namespace App\Modules\ISS\src\Services\EducationExam;

interface EducationExamRepoInterface
{
    /**
     * Запрос БД извлеч все экзаменационные вопросы теста для данной точки обучающего маршрута
     * (вместе с вериантами ответов, если они есть)
     * @param array $inputData $inputData['id'] код реальной точки учебного маршрута
     * @return array
     *               [
     *                 ['rerp_id'=>, 'erp_id'=>, 'questionId'=>, 'questionName'=>, 'questionText'=>],
     *                 ['rerp_id'=>, 'erp_id'=>, 'questionId'=>, 'questionName'=>, 'questionText'=>],
     *                 ...
     *               ]
     */
    public function getExamQuestions(array $inputData): array;

    /**
     * Запрос БД извлеч все ответы для экзаменационногоо вопроса теста
     * @param array $inputData $inputData['questionId'] код вопроса для контрольного теста
     * @return array
     *               [
     *                 ['id' => , 'answer' => ],
     *                 ['id' => , 'answer' => ],
     *                 ['id' => , 'answer' => ],
     *                 ...
     *               ]
     */
    public function getExamAnswers(array $inputData): array;

    /**
     * Запрос БД найти количество сложных вопросов теста для данной реальной точки учебного маршрута
     * @param array $inputData $inputData['id'] код реальной точки маршрута
     * @return array ['countOfComplicatedQuestions' => ]
     */
    public function complicatedQuestionsCount(array $inputData): array;

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
    public function getQuestionsWithAnswers(array $inputData): array;

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
    public function getRealRouteIdForRealPointBelongs(array $inputData): array;

    /**
     * Запрос БД обновить значение пройденной точки маршрута для заданного пользователя и его реального маршрута
     * @param array $inputData
     *               код реальной точки маршрута (которую заносим в real_education_route_of_users.last_pass_point)
     *                   $inputData['point_id']
     *               код реального маршрута полользователя который обновляем
     *                   $inputData['real_education_route_id']
     * @return bool
     */
    public function updateLastPassPoint(array $inputData): bool;

    /**
     * Запрос БД выбрать адреса почты всех преподавателей, относящихся к заданной организации
     * @param array $inputData
     *              название организации
     *                  $inputData['organization']
     * @return array [ ['teacher_email' => 'mail1'], ['teacher_email' => 'mail2'], ..... ]
     */
    public function getTeachersOfOrganization(array $inputData): array;

    /**
     * Запрос БД найти код пользователя ИОС (сдавашего экзамен)
     * и код реальной точки обучающего маршрута (для которой сдвали экзамен) по одноразовому коду проверки у преподавателя
     * @param array $inputData
     *               одноразовый код проверки, переданный преподавателю
     *                   $inputData['exam_check_code']
     * @return array ['iss_user_id', 'real_route_point_id']
     */
    public function getUserAndPointDataByCheckCode(array $inputData): array;

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
    public function getCheckCodeByUserIdAndRealPointId(array $inputData): array;

    /**
     * Запрос БД удалить запись из таблицы для заданного проверочного кода
     * @param array $inputData
     *               одноразовый код проверки, переданный преподавателю
     *                   $inputData['exam_check_code']
     *                флаг что требуется мягкое удаление
     *                    $inputData['soft_delete']
     * @return bool
     */
    public function delCheckCode(array $inputData): bool;

    /**
     * Запрос БД создать запись в таблице для заданного проверочного кода
     * @param array $inputData
     *               код пользователя ИОС сдающего экзамен
     *                   $inputData['iss_user_id']
     *               код реальной точки маршрута
     *                   $inputData['real_route_point_id']
     *                одноразовый код для проверки экзамена
     *                    $inputData['exam_check_code']
     * @return bool
     */
    public function makeCheckCode(array $inputData): bool;









    /**
     * Запрос БД получить код маршрута (из справочника) по коду реальной точки маршрута
     * @param array $inputData
     *               код реальной точки маршрута
     *                   $inputData['id']
     * @return array ['route_id' => ]
     */
    public function getRealRouteIdByRealPointId(array $inputData): array;

    /**
     * Запрос БД получить код последней пройденной точки маршрута пользователя
     * @param array $inputData
     *               код маршрута из справочника
     *                   $inputData['route_id']
     *               код пользователя ИОС
     *                   $inputData['user_data_id']
     * @return array ['lpp_id' => ]
     */
    public function getLPPid(array $inputData): array;

    /**
     * Запрос БД получить код первой (по порядку) точки на обучающем мершруте
     * @param array $inputData
     *               код маршрута пользователя
     *                   $inputData['route_id']
     * @return array ['id' => ]
     */
    public function getFirstRoutePoint(array $inputData): array;

    /**
     * Запрос БД получить значение позиции для последней пройденной точки реального обучающего маршрута
     * @param array $inputData
     *               код реальной точки маршрута
     *                   $inputData['id']
     * @return array ['position' => ]
     */
    public function getLPPposition(array $inputData): array;

    /**
     * Запрос БД получить код реальной точки маршрута, следующей за последней пройденной точкой
     * @param array $inputData
     *               позиция на маршруте последней пройденной точки
     *                   $inputData['position']
     *               код маршрута из справочника
     *                    $inputData['route_id']
     * @return array ['id' => ]
     */
    public function getNextExamPoint(array $inputData): array;

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
    public function getAnswersText(array $inputData): array;

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
    public function getQuestionsText(array $inputData): array;
}
