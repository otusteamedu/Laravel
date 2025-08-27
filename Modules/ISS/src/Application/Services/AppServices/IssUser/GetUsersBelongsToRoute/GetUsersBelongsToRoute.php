<?php

namespace ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute;

use ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute\InputDTO;
use ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute\OutputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as userInputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId\GetAllRealRoutesByRefRouteId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId\InputDTO as reruInputDTO;

class GetUsersBelongsToRoute
{
    private GetUserData $getUserData;
    private GetAllRealRoutesByRefRouteId $getAllRealRoutesByRefRouteId;

    public function __construct(
        GetUserData $getUserData,
        GetAllRealRoutesByRefRouteId $getAllRealRoutesByRefRouteId,
    )
    {
        $this->getUserData = $getUserData;
        $this->getAllRealRoutesByRefRouteId = $getAllRealRoutesByRefRouteId;
    }

    /**
     * Получить всех пользователей ИОС относящихся к заданному маршруту
     * @param InputDTO $inputData
     * @return array<OutputDTO>
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): array
    {
        //получаем все реальные маршруты, относящиеся к заданному справочному маршруту
        try {
            $realRoutes = ($this->getAllRealRoutesByRefRouteId)(new reruInputDTO(refRouteId: $inputData->routeId));
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("getAllRealRoutesByRefRouteId Service Error:{$e->getMessage()}", 500);
        }

        //для каждого реального маршрута достаем данные по его пользователю
        $issUserData = [];
        foreach ($realRoutes as $realRoute) {
            try {
                $issUserData[] = ($this->getUserData)(new userInputDTO(
                    fieldName: 'id',
                    fieldValue: $realRoute->issUserId,
                ));
            } catch (\Error | \Exception $e) {
                //запись влог
                throw new \Exception("getUsersBelongsToRoute Service Error:{$e->getMessage()}", 500);
            }
        }

        //записываем результат в формат выходного ДТО
        return array_map(
            function ($tmp) {
                return new OutputDTO(
                    email: $tmp->email,
                    name: $tmp->name,
                    secondName: $tmp->secondName,
                    lastName: $tmp->lastName
                );
            },
            $issUserData
        );
    }
}
