<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\delCheckCode;

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
