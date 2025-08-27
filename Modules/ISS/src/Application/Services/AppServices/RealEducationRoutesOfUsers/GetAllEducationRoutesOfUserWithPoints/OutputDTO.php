<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\PointDTO;

/**
 * @var float $readyPercent       процент прохождения обучающего маршрута
 * @var array<PointDTO[]> $points массив с данными для каждой точки маршрута
 * @var string $routeName         название обучающего маршрута
 * @var int $routeId              код реального обучающего маршрута пользователя
 */

class OutputDTO
{
    public function __construct(
        public float $readyPercent,
        public array $points,
        public string $routeName,
        public int $routeId
    )
    {
    }
}
