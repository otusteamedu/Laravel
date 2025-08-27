<?php

namespace ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints;

use ISS\App\Application\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints\InputDTO;
use ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints\SinglePointDTO;
use ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints\OutputDTO;

class GetAllRoutePoints
{
    private EducationRoutePointRepoInterface $repository;

    public function __construct(EducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать основные данные всех справочных точек обучающих маршрутов (показать справочник точек для маршрутов)
     * @param InputDTO $inputData
     * @return null|OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->getAllReferenceRoutePoints(['returned_fields' => $inputData->returnedFields]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = null;
        }

        if (!is_null($result)) {
            return new OutputDTO(
                array_map(
                    function ($point) {
                        return new SinglePointDTO(
                            pointId: $point['id'],
                            pointName: $point['name'],
                        );
                    },
                    $result
                )
            );
        } else {
            return null;
        }
    }
}
