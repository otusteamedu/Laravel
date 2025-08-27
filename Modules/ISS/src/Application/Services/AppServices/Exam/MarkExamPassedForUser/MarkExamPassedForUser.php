<?php

namespace ISS\App\Application\Services\AppServices\Exam\MarkExamPassedForUser;

use ISS\App\Application\Services\AppServices\Exam\MarkExamPassedForUser\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\MarkExamPassedForUser\OutputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\UpdateLastPassPoint\UpdateLastPassPoint;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\UpdateLastPassPoint\InputDTO as updateLppInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as realPointInputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\GetRouteOfUserByRefRouteId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\InputDTO as reruInputDTO;

class MarkExamPassedForUser
{
    private UpdateLastPassPoint $updateLastPassPoint;
    private GetRealRoutePointById $getRealRoutePointById;
    private GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId;

    public function __construct(
        UpdateLastPassPoint $updateLastPassPoint,
        GetRealRoutePointById $getRealRoutePointById,
        GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId

    )
    {
        $this->updateLastPassPoint = $updateLastPassPoint;
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getRouteOfUserByRefRouteId = $getRouteOfUserByRefRouteId;
    }

    /**
     * Поставить отметку что экзамен для пользователя сдан для заданной точки учебного маршрута
     * (заносит номер точки в таблицу real_education_routes_of_users.last_pass_point_id)
     * @param InputDTO $inputDTO
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        try {

            //получаем данные раельной точки маршрута по ее ID, из них берем код справочного маршрута
            $refRouteId = ($this->getRealRoutePointById)(new realPointInputDTO(id: $inputDTO->realRoutePointId))->routeId;

            //получаем код реального маршрута по коду справочного маршруа и коду пользователя ИОС
            $realRouteId = ($this->getRouteOfUserByRefRouteId)(new reruInputDTO(
                issUserId: $inputDTO->issUserId,
                refRouteId: $refRouteId
            ))->id;

            //обновляем lpp для найденного реального маршрута
            $result = ($this->updateLastPassPoint)(new updateLppInputDTO(
                reruId: $realRouteId,
                newLppId: $inputDTO->realRoutePointId
            ))->operationResult;
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = false;
        }

        return new OutputDTO(result: $result);
    }
}
