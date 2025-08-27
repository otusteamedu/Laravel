<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\InputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\OutputDTO;

class GetRouteOfUserByRefRouteId
{
    private RealEducationRoutesOfUsersRepoInterface $repository;

    public function __construct(RealEducationRoutesOfUsersRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные реального маршрута пользователя ИОС
     * по коду пользователя и коду справочного маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->getRealRouteOfUserByRefRouteId(
                [
                    'user_data_id' => $inputData->issUserId,
                    'route_id' => $inputData->refRouteId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись влог
            throw new \Exception("error repo getRealRouteOfUserByRefRouteId: {$e->getMessage()}", 500);
        }

        if (empty($result)) {
            return null;
        } else {
            return new OutputDTO(
                id: $result['id'],
                userDataId: $result['user_data_id'],
                routeId: $result['route_id'],
                lastPassPointId: $result['last_pass_point_id'],
            );
        }
    }
}
