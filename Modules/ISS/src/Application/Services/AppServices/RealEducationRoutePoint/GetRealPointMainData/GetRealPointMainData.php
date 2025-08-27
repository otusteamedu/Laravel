<?php

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData;

use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\InputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\OutputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as rerpInputDTO;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\GetRoutePointById;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\InputDTO as erpInputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\GetRouteOfUserByRefRouteId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\InputDTO as reruInputDTO;
use ISS\App\Application\Services\EducationRoute\GetRouteById\GetRouteById;
use ISS\App\Application\Services\EducationRoute\GetRouteById\InputDTO as erInputDTO;
use ISS\App\Domain\RealEducationRoutePoint\RealEducationRoutePoint;

class GetRealPointMainData
{
    private GetRealRoutePointById $getRealRoutePointById;
    private GetRoutePointById $getRoutePointById;
    private GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId;
    private GetRouteById $getRouteById;

    public function __construct(
        GetRealRoutePointById $getRealRoutePointById,
        GetRoutePointById $getRoutePointById,
        GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId,
        GetRouteById $getRouteById
    )
    {
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getRoutePointById = $getRoutePointById;
        $this->getRouteOfUserByRefRouteId = $getRouteOfUserByRefRouteId;
        $this->getRouteById = $getRouteById;
    }

    /**
     * Достать общие данные для точки обучающего маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        //достаем реальную точку маршрута по ее id из InputDTO
        try {
            $rerp = ($this->getRealRoutePointById)(new rerpInputDTO(
                id: $inputData->id,
                returnedFields: ['*']
            ));
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error service getRealRoutePointById: {$e->getMessage()}");
        }

        //используя данные реальной точки (route_id) достаем справочный маршрут пользователя
        try {
            $er = ($this->getRouteById)(new erInputDTO(
                id: $rerp->routeId,
            ));
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error service getRouteById: {$e->getMessage()}");
        }

        //используя данные реальной точки (route_point_id) достаем ее справочную точку маршрута
        try {
            $erp = ($this->getRoutePointById)(new erpInputDTO(id: $rerp->routePointId));
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error service getRoutePointById: {$e->getMessage()}");
        }

        //используя данные реальной точки (route_id) и код пользователя ИОС из InputDTO достаем реальный маршрут пользователя
        try {
            $reru = ($this->getRouteOfUserByRefRouteId)(new reruInputDTO(
                issUserId: $inputData->userDataId,
                refRouteId: $rerp->routeId
            ));
        } catch (\Error | \Exception $e) {
            //запись в лог
            //return null;
            throw new \Exception("error service getRouteOfUserByRefRouteId: {$e->getMessage()}");
        }

        //используя данные реального маршрута (last_pass_point_id) достаем последнюю пройденную точку маршрута
        try {
            $lpp = ($this->getRealRoutePointById)(new rerpInputDTO(
                id: $reru->lastPassPointId,
                returnedFields: ['*']
            ));
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("error service getRealRoutePointById: {$e->getMessage()}");
        }

        //сводим данные в выходное DTO (применяя для examResult бизнес правило из домена)
        return new OutputDTO(
            routePointId: $rerp->id,
            examDate: $rerp->examDate,
            routeName: $er->routeName,
            pointName: $erp->pointName,
            lastPassedExamDate: $lpp->examDate,
            examResult: (
                new RealEducationRoutePoint(
                    $rerp->id,
                    $erp->pointId,
                    $er->routeId,
                    $rerp->examDate,
                    $rerp->position,
                )
            )->pointStatus($lpp->position)
        );
    }
}
