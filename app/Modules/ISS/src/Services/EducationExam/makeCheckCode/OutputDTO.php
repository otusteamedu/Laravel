<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\makeCheckCode;

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
