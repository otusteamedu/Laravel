<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers;

/**
 * @var array $questionIds массив кодов вопросов (данные для которых извлекаем) ['id1', 'id2', ....]
 */

class InputDTO
{
    public function __construct(
        public array $questionIds,
    )
    {
    }
}
