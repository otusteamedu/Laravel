<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetAllRERPsForExamDateCheck;

/**
 * @var int $routeId id справочного обучающего маршрута
 * @var string $routeName название справочного обучающего маршрута
 * @var string $pointName название справочной точки обучающего маршрута, относ-я к реальной точке для которой подходит срок сдачи экзамена
 * @var string $examDate запланированная дата экзамена
 */

class OutputDTO
{
    public function __construct(
        public int $routeId,
        public string $routeName,
        public string $pointName,
        public string $examDate,
    )
    {
    }
}
