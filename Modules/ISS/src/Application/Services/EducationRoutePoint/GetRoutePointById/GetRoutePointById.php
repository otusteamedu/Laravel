<?php

namespace ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById;

use ISS\App\Application\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\InputDTO;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\OutputDTO;

class GetRoutePointById
{
    private EducationRoutePointRepoInterface $repository;

    public function __construct(EducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать данные справочной точки маршрута по ее ID
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->getRoutePointById(
                [
                    'id' => $inputData->id,
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error repo getRoutePointById: {$e->getMessage()}", 500);
        }

        if (empty($result)) {
            return null;
        } else {
            return new OutputDTO(
                pointId: $result['id'],
                pointName: $result['name']
            );
        }
    }
}
