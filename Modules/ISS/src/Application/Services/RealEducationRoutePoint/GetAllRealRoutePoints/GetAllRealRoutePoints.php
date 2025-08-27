<?php

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePoints;

use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePoints\OutputDTO;

class GetAllRealRoutePoints
{
    private RealEducationRoutePointRepoInterface $repository;

    public function __construct(RealEducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные для всех реальных точек всех обучающих маршрутов
     * @return array<OutputDTO>
     * @throws \Exception
     */
    public function __invoke(): array
    {
        try {
            $result = $this->repository->getAllRealRoutePoints();
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("getAllRealRoutePoints error: {$e->getMessage()}", 500);
        }

        return array_map(
            function ($tmp) {
                return new OutputDTO(
                    id: $tmp['id'],
                    routeId: $tmp['route_id'],
                    routePointId: $tmp['route_point_id'],
                    examDate: $tmp['exam_date'],
                    position: $tmp['position']
                );
            },
            $result
        );
    }
}
