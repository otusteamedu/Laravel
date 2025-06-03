<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData;

use \DateTimeInterface;

/**
 * @var int $routePointId код реальной точки обучающего маршрута
 * @var string $examDate дата экзамена для реальной точки обучающего маршрута
 * @var string $routeName название обучающего маршрута
 * @var string $pointName название точки обучающего маршрута (из справочника)
 * @var string $lastPassedExamDate дата сдачи последнего контрольного теста для текущего маршрута (на котором находится рассматриваемая реальная точка)
 * @var string $examResult статус контрольного теста точки маршрута для текущего пользователя
 */

class OutputDTO
{
    public function __construct(
        public int $routePointId,
        public string $examDate,
        public string $routeName,
        public string $pointName,
        public string $lastPassedExamDate,
        public string $examResult
    )
    {
    }
}
