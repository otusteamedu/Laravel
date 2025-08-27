<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText;

/**
 * @var array $answersIds массив id ответов к экзаменационным вопросам, для которых извлекаем их тексты ['id1', 'id2', ...]
 */

class InputDTO
{
    public function __construct(
        public array $answersIds,
    )
    {
    }
}
