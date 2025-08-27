<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\DelCheckCode;

/**
 * @var bool $result результат операции (true -- успешно, false -- произошла ошибка)
 */

class OutputDTO
{
    public function __construct(
        public bool $result
    )
    {
    }
}
