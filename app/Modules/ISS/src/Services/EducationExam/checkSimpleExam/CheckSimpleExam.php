<?php

namespace App\Modules\ISS\src\Services\EducationExam\checkSimpleExam;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\OutputDTO;

class CheckSimpleExam
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Проверяет ответы на вопросы для порстого экзамена
     * если процент неправильных ответов меньше допустимого -- экзамен сдан, если нет -- провален
     * При передаче в сервис списка вопросов, содержащих хотя-бы один сложный вопрос,
     * вернет отметку что экзамен не сдан.
     *
     * Возвращает отметку о статусе экзамена и массив с проверенными вопросами
     * @param InputDTO $inputDTO
     * @return OutputDTO если произошла ошибка, вернет null
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        $questionsCount = count($inputDTO->questionsWithAnswers);
        $errors = 0;
        $checkedQuestions = [];

        //достаем вопросы с ответами из БД (со всеми ответами)
        $questionIds = array_column($inputDTO->questionsWithAnswers, 'questionId');
        try {
            $questionsWithAnswers = $this->repository->getQuestionsWithAnswers($questionIds);
        } catch (\Error | \Exception $e) {
            $questionsWithAnswers = [];
            //запись в лог
        }

        if (!empty($questionsWithAnswers)) {
            //преобразуем массив из БД к виду [ ['questionId'=>, 'rightAnswerId' =>], ['questionId'=>, 'rightAnswerId' =>], ...]
            //если вдруг попался вопрос без ответов (сложный) то поставит ему rightAnswerId => null
            //если у простого вопроса с ответами в базе по ошибке нет ответа с is_right = Y то также rightAnswerId => 0
            $questionsWithRightAnswers = array_map(
                function ($tmp) {
                    $answerId = null;
                    foreach($tmp['exam_answer'] as $answer) {
                        $answerId = 0;
                        if ($answer['is_right'] == 'Y') {
                            $answerId = $answer['id'];
                            break;
                        }
                    }
                    return ['questionId' => $tmp['id'], 'rightAnswerId' => $answerId];
                },
                $questionsWithAnswers
            );

            //сравниваем массивы с ответами из формы и с правильными ответами из БД
            foreach ($inputDTO->questionsWithAnswers as $currentQuestion) {
                $index = array_search(
                    $currentQuestion['questionId'],
                    array_column($questionsWithRightAnswers, 'questionId')
                );

                //проверяем что текущий вопрос из формы есть в списке вопросов из БД
                if ($index !== false) {
                    $checkedQuestions[] = [
                        'questionId' => $currentQuestion['questionId'],
                        'answerId' => $currentQuestion['answerId'],
                        'rightAnswerId' => $questionsWithRightAnswers[$index]['rightAnswerId']
                    ];

                    if (is_null($currentQuestion['answerId']) ||                          //в форме ошибка
                        is_null($questionsWithRightAnswers[$index]['rightAnswerId']) ||   //вопрос 'сложный'
                        $currentQuestion['answerId'] != $questionsWithRightAnswers[$index]['rightAnswerId']
                    ) {
                        $errors = $errors + 1;
                    }
                } else {
                    //запись в лог (ошибка в БД нет одного из запрашиваемых вопросов)
                    return new OutputDTO(passed: false, checkedQuestions: []);
                }
            }
        } else {
            //запись в лог (ошибка в БД нет запрашиваемых вопросов или при их поиске произошла ошибка)
            return new OutputDTO(passed: false, checkedQuestions: []);
        }

        //проверяем что на проверку переданы только простые вопросы (с ответами)
        if (in_array(null, array_column($questionsWithRightAnswers, 'rightAnswerId'), true)) {
            //передан хотя бы один сложный вопрос
            return new OutputDTO(passed: false, checkedQuestions: $checkedQuestions);
        } else {
            //переданы только простые вопросы
            //рассчитываем не привышен ли допустимый процент ошибок
            if (100*($errors/$questionsCount) < $inputDTO->errorsAllowed) {
                return new OutputDTO(passed: true, checkedQuestions: $checkedQuestions);
            } else {
                return new OutputDTO(passed: false, checkedQuestions: $checkedQuestions);
            }
        }
    }
}
