<?php

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition;

use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition\InputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition\OutputDTO;

class GetNextRealRoutePointByPosition
{
    private RealEducationRoutePointRepoInterface $repository;

    public function __construct(RealEducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные реальной точки маршрута, следующей за указанной реальной точкой
     * по коду справочного маршрута и значению позиции указанной реальной точки маршрута
     * @param InputDTO
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getNextRealRoutePointByPosition(
                [
                    'position' => $inputData->position,
                    'route_id' => $inputData->routeId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getNextRealRoutePointByPositio: {$e->getMessage()}");
        }

        return new OutputDTO(
            id: $result[0]['id'] ?? null,
            routePointId: $result[0]['route_point_id'] ?? null,
            routeId: $result[0]['route_id'] ?? null,
            examDate: $result[0]['exam_date'] ?? null,
            position: $result[0]['position'] ?? null,
        );
    }
}
