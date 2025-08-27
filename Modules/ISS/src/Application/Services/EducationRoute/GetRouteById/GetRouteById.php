<?php

namespace ISS\App\Application\Services\EducationRoute\GetRouteById;

use ISS\App\Application\Services\EducationRoute\EducationRouteRepoInterface;
use ISS\App\Application\Services\EducationRoute\GetRouteById\InputDTO;
use ISS\App\Application\Services\EducationRoute\GetRouteById\OutputDTO;

class GetRouteById
{
    private EducationRouteRepoInterface $repository;

    public function __construct(EducationRouteRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные справочного маршрута по его ID
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->getRouteById(['id' => $inputData->id]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error repository getRouteById: {$e->getMessage()}", 500);
        }

        if (empty($result)) {
            return null;
        } else {
            return new OutputDTO(
                routeId: $result['id'],
                routeName: $result['name'],
            );
        }
    }
}
