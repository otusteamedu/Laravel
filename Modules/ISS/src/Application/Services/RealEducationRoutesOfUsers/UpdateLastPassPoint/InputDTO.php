<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\UpdateLastPassPoint;

/**
 * @var int $reruId код реального обучающего маршрута
 * @var int $newLppId код реальной точки маршрута, который будет записан при обновлении lpp реального маршрута
 */

class InputDTO
{
    public function __construct(
        public int $reruId,
        public int $newLppId,
    )
    {
    }
}
