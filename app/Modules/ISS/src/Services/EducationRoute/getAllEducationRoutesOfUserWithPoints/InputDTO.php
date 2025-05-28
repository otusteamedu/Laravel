<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints;

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
