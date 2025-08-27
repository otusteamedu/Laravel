<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\InputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\SingleRealRouteDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\OutputDTO;

class GetAllRealRoutesOFUserByUserId
{
    private RealEducationRoutesOfUsersRepoInterface $repository;

    public function __construct(RealEducationRoutesOfUsersRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные всех реальных маршрутов пользователя ИОС
     * по коду пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getAllRealRoutesOfUserByUserId(['user_data_id' => $inputData->issUserId]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error repo getAllRealRoutesOfUserByUserId: {$e->getMessage()}", 500);
        }

        return new OutputDTO(
            routes: array_map(
                function ($tmp) {
                    return new SingleRealRouteDTO(
                        id: $tmp['id'],
                        userDataId: $tmp['user_data_id'],
                        routeId: $tmp['route_id'],
                        lastPassPointId: $tmp['last_pass_point_id'],
                    );
                },
                $result
            )
        );
    }
}
