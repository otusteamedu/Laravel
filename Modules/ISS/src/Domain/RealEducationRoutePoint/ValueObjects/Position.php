<?php

namespace ISS\App\Domain\RealEducationRoutePoint\ValueObjects;

use InvalidArgumentException;

/** @var int|null $position позиция реальной точки в обучающем маршруте */

class Position
{
    public int|null $position;

    public function __construct(int|null $position)
    {
        if (!is_null($position)) {
            if ($position < 0) {
                throw new InvalidArgumentException("Real route point position must be positive integer");
            }
        }
        $this->position = $position;
    }

}
