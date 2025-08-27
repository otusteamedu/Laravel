<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ChooseExamCheckTeacher;

/**
 * @var int $issUserId код пользователя ИОС, экзамен которого проверяем
 */

class InputDTO
{
    public function __construct(
        public int $issUserId
    )
    {
    }
}
