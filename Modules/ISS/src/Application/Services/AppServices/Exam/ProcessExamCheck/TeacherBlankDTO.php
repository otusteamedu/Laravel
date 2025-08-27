<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck;

/**
 * @var string $email почта преподавателя
 * @var string $examCheckCode одноразовый код проверки экзамена
 * @var array $checkedQuestions проверенные вопросы с ответами (для простых вопросов проставлены ответы, для сложных null)
 * [
 *             [ 'questionId' => , 'questionText' =>, 'answerId' => , 'answerText'=> , 'rightAnswerId' => , 'rightAnswerText'=>],
 *             [ 'questionId' => , 'questionText' =>, 'answerId' => , 'answerText'=> , 'rightAnswerId' => , 'rightAnswerText'=>],
 *             .......
 * ]
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
