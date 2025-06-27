<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\fillExamBlank;


/**
 * @var array<QuestionWithAnswersWithTextDTO> $examBlank массив провереных экзаменационных вопросов,
 *           дополненный текстами вопросов и ответов
 *           (если вопрос пришел с 'rightAnswerId' => NULL то текст правильного ответа не добавляется)
 */

class OutputDTO
{
    public function __construct(
        public array $examBlank,
    )
    {
    }
}
