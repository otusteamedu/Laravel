<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\fillExamBlank;

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
