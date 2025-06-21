<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser;

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
