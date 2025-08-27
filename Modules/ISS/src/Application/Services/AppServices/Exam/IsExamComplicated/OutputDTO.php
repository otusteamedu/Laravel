<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\IsExamComplicated;

/**
 * @var bool $isComplicated отметка о том что тип контрольного теста сложный (false -- простой тест \ true -- сложный тест)
 */

class OutputDTO
{
    public function __construct(
        public bool $isComplicated
    )
    {
    }
}
