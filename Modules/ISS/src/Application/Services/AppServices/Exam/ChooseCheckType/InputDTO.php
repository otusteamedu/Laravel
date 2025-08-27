<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\Exam\ChooseCheckType;

/**
 * @var int $id код реальной точки учебного маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
