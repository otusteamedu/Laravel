<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\IsExamComplicated;

/**
 * @var int $id код реальной точки маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
