<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\MarkExamPassedForUser;

/**
 * @var bool $result результат операции (true - успешно, false - где то ошибка)
 */

class OutputDTO
{
    public function __construct(
        public bool $result
    )
    {
    }
}
