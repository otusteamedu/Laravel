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
}
