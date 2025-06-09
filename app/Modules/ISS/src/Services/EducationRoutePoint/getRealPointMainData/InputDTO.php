<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData;

/**
 * @var int $id         код реальной точки обучающего маршрута
 * @var int $userDataId код пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public int $id,
        public int $userDataId
    )
    {
    }
}
