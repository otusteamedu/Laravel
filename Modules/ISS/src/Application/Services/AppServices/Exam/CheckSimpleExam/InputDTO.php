<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\CheckSimpleExam;

/**
 * @var int   $errorsAllowed        допустимый процент неверных ответов (% от количества вопросов в экзамене)
 * @var array $questionsWithAnswers массив вопросов с ответами из формы экзамена
 *      [
 *         ['questionId'=> 3245, 'answerId' => 2345],
 *         ['questionId'=> 35, 'answerId' => 323],
 *         ['questionId'=> 35, 'answerId' => 'tfygjh qwsrdtfh wasercthgj'], //для сложного вопроса
 *         ...
 *      ]
 */

class InputDTO
{
    public function __construct(
        public int   $errorsAllowed,
        public array $questionsWithAnswers
    )
    {
    }
}
