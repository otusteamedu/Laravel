<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById;

/**
 * @var int|null $id код реальной точки обучающего маршрута или null (т.к. last_pass_point_id может быть не определена)
 * @var array $returnedFields извлекааемые поля
 */

class InputDTO
{
    public function __construct(
        public int|null $id,
        public array $returnedFields = ['*'],
    )
    {
    }
}
