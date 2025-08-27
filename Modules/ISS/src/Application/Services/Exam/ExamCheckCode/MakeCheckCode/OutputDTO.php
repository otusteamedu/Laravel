<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\MakeCheckCode;

/**
 * @var string|null $examCheckCode одноразовый код для проверки экзамена
 */

class OutputDTO
{
    public function __construct(
        public string|null $examCheckCode,
    )
    {
    }
}
