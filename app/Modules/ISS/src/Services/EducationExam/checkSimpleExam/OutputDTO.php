<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\checkSimpleExam;

/**
 * @var bool  $passed отметка что экзамен сдан (true --сдан, false --нет)
 * @var array $checkedQuestions массив с провереными вопросами
 *           (если по ошибке в БД у вопроса не отмечен правильныйй ответ вернет 'rightAnswerId' => 0)
 *           (если по в сервис передали сложный вопрос (без ответов) вернет 'rightAnswerId' => null)
 *            [
 *             [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *             [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *             ....
 *            ]
 */

class OutputDTO
{
    public function __construct(
        public bool  $passed,
        public array $checkedQuestions
    )
    {
    }
}
