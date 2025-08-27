<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText;

/**
 * @var array $questionIds массив id экзаменационных вопросов, для которых извлекаем их тексты ['id1', 'id2', ...]
 */

class InputDTO
{
    public function __construct(
        public array $questionIds,
    )
    {
    }
}
