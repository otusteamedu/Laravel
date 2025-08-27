<?php

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId;

use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId\InputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId\OutputDTO;

class GetFirstRealRoutePointByRouteId
{
    private RealEducationRoutePointRepoInterface $repository;

    public function __construct(RealEducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные реальной точки обучающего маршрута (которая является первой на нем)
     * по коду справочного маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = ($this->repository->getFirstRoutePoint(['route_id' => $inputData->refRouteId]))['id'];
        } catch (\Error | \Exception $e) {
            //запись влог
            throw new \Exception("repo error getFirstRealRoutePointByRouteId{$e->getMessage()}", 500);
        }

        return new OutputDTO(
            id: $result['id'] ?? null,
            routePointId: $result['route_point_id'] ?? null,
            routeId: $result['route_id'] ?? null,
            examDate: $result['exam_date'] ?? null,
            position: $result['position'] ?? null,
        );
    }
}
