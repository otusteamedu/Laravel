<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\processExamCheck;

/**
 * @var string $email почта преподавателя
 * @var string $examCheckCode одноразовый код проверки экзамена
 * @var array $checkedQuestions проверенные вопросы с ответами (для простых вопросов проставлены ответы, для сложных null)
 *            [
 *             [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *             [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *             .......
 *            ]
 *
 */

class TeacherBlankDTO
{
    public function __construct(
        public string $email,
        public string $examCheckCode,
        public array $checkedQuestions,
    )
    {
    }
}
