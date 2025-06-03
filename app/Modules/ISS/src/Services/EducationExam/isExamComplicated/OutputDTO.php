<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\isExamComplicated;

/**
 * @var bool $isExamComplicated отметка о том что тип контрольного теста сложный (false -- простой тест \ true -- сложный тест)
 */

class OutputDTO
{
    public function __construct(
        public bool $isExamComplicated
    )
    {
    }
}
