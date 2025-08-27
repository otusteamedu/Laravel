<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId\InputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId\OutputDTO;

class GetAllRealRoutesByRefRouteId
{
    private RealEducationRoutesOfUsersRepoInterface $repository;

    public function __construct(RealEducationRoutesOfUsersRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные всех реальных маршрутов пользователей ИОС
     * по коду справочного маршрута
     * @param InputDTO $inputData
     * @return array<OutputDTO>
     */
    public function __invoke(InputDTO $inputData): array
    {
        try {
            $result = $this->repository->getAllRealRoutesByRefRouteId(['route_id' => $inputData->refRouteId]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("getAllRealRoutesByRefRouteId fail {$e->getMessage()}", 500);
        }

        return array_map(
            function ($result) {
                return new OutputDTO(
                    id: $result['id'],
                    refRouteId: $result['route_id'],
                    issUserId: $result['user_data_id'],
                    lastPassedPointId: $result['last_pass_point_id'],
                );
            },
            $result
        );
    }
}
