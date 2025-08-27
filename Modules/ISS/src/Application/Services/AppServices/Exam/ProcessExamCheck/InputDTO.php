<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck;

/**
 * @var int   $issUserId код пользователя ИОС сдеющего экзамен
 * @var int   $realRoutePointId код реальной точки обучающего маршрута для которой сдается экзамен
 * @var array $questionsWithAnswers вопросы с ответами на экзамен из формы ввода (IssRoutePointController)
 *            [
 *             ['questionId' => , 'answerId' => ],
 *             ['questionId' => , 'answerId' => ],
 *             ....
 *            ]
 */

class InputDTO
{
    public function __construct(
        public int   $issUserId,
        public int   $realRoutePointId,
        public array $questionsWithAnswers,
    )
    {
    }
}
