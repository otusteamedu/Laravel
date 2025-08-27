<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId;

use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\InputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\SingleRealPointDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\OutputDTO;

class GetAllRealRoutePointsByRouteId
{
    private RealEducationRoutePointRepoInterface $repository;

    public function __construct(RealEducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные для всех реальных точек обучающего маршрута по коду этого маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
       try {
           $result = $this->repository->getAllRealRoutePointsByRouteId(['route_id' => $inputDTO->routeId]);
       } catch (\Error | \Exception $e) {
           //запись в лог
           throw new \Exception("error repo getAllRealRoutePointsByRouteId: {$e->getMessage()}");
       }

       return new OutputDTO(
           array_map(
               function ($tmp) {
                   return new SingleRealPointDTO(
                       id: $tmp['id'],
                       routePointId: $tmp['route_point_id'],
                       routeId: $tmp['route_id'],
                       examDate: $tmp['exam_date'],
                       position: $tmp['position'],
                   );
               },
               $result
           )
       );
    }
}
