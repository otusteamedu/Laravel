<?php

namespace ISS\App\Application\Services\AppServices\Exam\CheckSimpleExam;

use ISS\App\Application\Services\AppServices\Exam\CheckSimpleExam\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\CheckSimpleExam\OutputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\GetQuestionsWithAnswers;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\InputDTO as getQuestionsInputDTO;

use ISS\App\Domain\Exam\Exam;


class CheckSimpleExam
{
    private GetQuestionsWithAnswers $getQuestionsWithAnswers;

    public function __construct(
        GetQuestionsWithAnswers $getQuestionsWithAnswers,

    )
    {
        $this->getQuestionsWithAnswers = $getQuestionsWithAnswers;
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

        //достаем вопросы с ответами из БД (со всеми ответами,
        // для тех id вопросов, которые переданы в бланке на проверку)
        $questionIds = array_column($inputDTO->questionsWithAnswers, 'questionId');

        try {
            $questionsWithAnswers = ($this->getQuestionsWithAnswers)(new getQuestionsInputDTO(questionIds: $questionIds));
        } catch (\Error | \Exception $e) {
            $questionsWithAnswers = null;
            //запись в лог
        }

        if (!is_null($questionsWithAnswers) && !empty($questionsWithAnswers->questionsWithAnswers)) {

            //переводим данные полученные из БД через сервис, в массив для передачи в конструктор домена
            $rawQuestionsWithAnswersFromDB = array_map(
                function ($tmp) {
                    $answers = array_map(
                        function ($answer) {
                            return [
                                'id' => $answer->id,
                                'answer_short_name' => $answer->answerName,
                                'answer' => $answer->answerText,
                                'question_id' => $answer->questionId,
                                'is_right' => $answer->isRight,
                            ];
                        },
                        $tmp->answers
                    );

                    return [
                        'id' => $tmp->id,
                        'short_question_name' => $tmp->questionName,
                        'question' => $tmp->questionText,
                        'point_id' => $tmp->refPointId,
                        'exam_answers' => $answers,
                        ];
                },
                $questionsWithAnswers->questionsWithAnswers
            );

            //создаем домен Экзамена
            $exam = new Exam($rawQuestionsWithAnswersFromDB, $inputDTO->questionsWithAnswers, $inputDTO->errorsAllowed);
            //проверяем экзамен
            $exam->checkExam();
            //получаем проверенные экзаменационные вопросы и отметку о прохождении экзамена
            $checkedQuestions = $exam->getCheckedQuestions();
            $isPassed = $exam->getExamResult();

            return new OutputDTO(passed: $isPassed, checkedQuestions: $checkedQuestions);
        } else {
            //запись в лог (ошибка в БД нет запрашиваемых вопросов или при их поиске произошла ошибка)
            return new OutputDTO(passed: false, checkedQuestions: []);
        }
    }
}
