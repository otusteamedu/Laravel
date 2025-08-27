<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints;

/**
 * @var int $id код пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
