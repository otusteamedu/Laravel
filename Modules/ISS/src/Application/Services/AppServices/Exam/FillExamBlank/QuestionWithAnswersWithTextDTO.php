<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\FillExamBlank;

/**
 * @var int $questionId код экзаменационного вопроса
 * @var string $questionText текст экзаменационного вопроса
 * @var int|string|null $answerId код ответа для экзаменационного вопроса (или письменый ответ для сложного вопроса)
 * @var string|null $answerText текст ответа для экзаменационного вопроса
 * @var int|null $rightAnswerId код правильного ответа для экзаменационного вопроса
 * @var string|null $rightAnswerText текст правильного ответа для экзаменационного вопроса
 */

class QuestionWithAnswersWithTextDTO
{
    public function __construct(
        public int             $questionId,
        public string          $questionText,
        public int|string|null $answerId,
        public string|null     $answerText,
        public int|null        $rightAnswerId,
        public string|null     $rightAnswerText,
    )
    {
    }
}
