<?php

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById;

use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\OutputDTO;

class GetRealRoutePointById
{
    private RealEducationRoutePointRepoInterface $repository;

    public function __construct(RealEducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить все данные для реальной точки обучающего маршрута, выбранной по ID
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        try {
            $result = $this->repository->getRealRoutePointById(
                ['id' => $inputDTO->id, 'returned_fields' => $inputDTO->returnedFields]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getRealRoutePointById: {$e->getMessage()}", 500);
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
