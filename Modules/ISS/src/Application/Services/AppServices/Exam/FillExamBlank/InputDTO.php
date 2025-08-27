<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\FillExamBlank;

/**
 * @var array $checkedQuestions массив провереных экзаменационных вопросов
 *            [
 *              [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *              [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *              .......
 *             ]
 */

class InputDTO
{
    public function __construct(
        public array $checkedQuestions,
    )
    {
    }
}
