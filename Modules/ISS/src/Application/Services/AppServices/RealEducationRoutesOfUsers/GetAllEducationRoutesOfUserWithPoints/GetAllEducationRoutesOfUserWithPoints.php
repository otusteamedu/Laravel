<?php

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints;

use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\InputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\OutputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\PointDTO;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\GetAllRealRoutesOFUserByUserId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\InputDTO as reruInputDTO;
use ISS\App\Application\Services\EducationRoute\GetRouteById\GetRouteById;
use ISS\App\Application\Services\EducationRoute\GetRouteById\InputDTO as erInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\GetAllRealRoutePointsByRouteId;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\InputDTO as rerpByRouteIdInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as rerpByIdInputDTO;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\GetRoutePointById;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\InputDTO as erpInputDTO;

use ISS\App\Domain\RealEducationRoutePoint\RealEducationRoutePoint;
use ISS\App\Domain\RealEducationRoutesOfUsers\RealEducationRoutesOfUser;

class GetAllEducationRoutesOfUserWithPoints
{
    private GetAllRealRoutesOFUserByUserId $getAllRealRoutesOfUserByUserId;
    private GetRouteById $getRouteById;
    private GetAllRealRoutePointsByRouteId $getAllRealRoutePointsByRouteId;
    private GetRealRoutePointById $getRealRoutePointById;
    private GetRoutePointById $getRoutePointById;

    public function __construct(
        GetAllRealRoutesOFUserByUserId $getAllRealRoutesOfUserByUserId,
        GetRouteById $getRouteById,
        GetAllRealRoutePointsByRouteId $getAllRealRoutePointsByRouteId,
        GetRealRoutePointById $getRealRoutePointById,
        GetRoutePointById $getRoutePointById
    )
    {
        $this->getAllRealRoutesOfUserByUserId = $getAllRealRoutesOfUserByUserId;
        $this->getRouteById = $getRouteById;
        $this->getAllRealRoutePointsByRouteId = $getAllRealRoutePointsByRouteId;
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getRoutePointById = $getRoutePointById;
    }


    /**
     * Получить все обучающие маршруты пользователя с точками маршрутов
     * @param InputDTO $inputData
     * @return OutputDTO[]
     */
    public function __invoke(InputDTO $inputData): array
    {
        $resultTmpArray = [];

        //получить все реальные обучающие маршруты пользователя ИОС
        $userRoutes = ($this->getAllRealRoutesOfUserByUserId)(new reruInputDTO(issUserId: $inputData->id));

        //для каждого маршрута в цикле выполнить
        $routeNum = 0; //номер элемента массива для обучающего маршрута пользователя
        foreach($userRoutes->routes as $route) {
            //используя данные реального маршрута (route_id) извлеч справочный маршрут
            $refRouteName = ($this->getRouteById)(new erInputDTO(id: $route->routeId))->routeName;

            //используя данные реального маршрута (last_pass_point_id) получить данные его последней пройденной точки
            //если ни одна точка не пройдена то null
            if (!is_null($route->lastPassPointId)) {
                $lppData = ($this->getRealRoutePointById)(new rerpByIdInputDTO(id: $route->lastPassPointId));
            } else {
                $lppData = null;
            }

            //используя данные реального маршрута (route_id) извлеч все реальные точки для этого маршрута из rerp
            $realPointsForRealRoute = ($this->getAllRealRoutePointsByRouteId)(new rerpByRouteIdInputDTO(
                routeId: $route->routeId,
            ));

            $pointNum = 0; //номер элемента в массиве точек данного маршрута
            //для каждой реальной точки
            foreach ($realPointsForRealRoute->realPoints as $realPoint) {
                //используя данные реальной точки (route_point_id) извлеч справочную точку маршрута
                $routePointName = ($this->getRoutePointById)(new erpInputDTO(id: $realPoint->routePointId))->pointName;

                //применить к точке бизнес правило определив ее статус (пройдена или нет)
                $realPointStatus = (
                    new RealEducationRoutePoint(
                        $realPoint->id,
                        $realPoint->routePointId,
                        $realPoint->routeId,
                        $realPoint->examDate,
                        $realPoint->position
                    )
                )->pointStatus($lppData->position ?? null);

                //заполнение временного массива для очередной точки текущего маршрута
                $resultTmpArray[$routeNum]['points'][$pointNum]['examDate'] =  $realPoint->examDate;
                $resultTmpArray[$routeNum]['points'][$pointNum]['realRoutePointId'] =  $realPoint->id;
                $resultTmpArray[$routeNum]['points'][$pointNum]['routePointName'] =  $routePointName;
                $resultTmpArray[$routeNum]['points'][$pointNum]['pass'] =  $realPointStatus;

                $pointNum++;
            }

            //подсчитать кол-во реальных точек маршрута, и кол-во пройденных точек по их статусам из массива rerp
            if (isset($resultTmpArray[$routeNum]['points'])) {
                //если для этого маршрута есть точки
                $allPointsAmount = count($resultTmpArray[$routeNum]['points']);
                $passedPointsAmount = count(
                    array_filter(
                        $resultTmpArray[$routeNum]['points'],
                        function($val) {
                            return $val['pass'] === 'passed';
                        }
                    )
                );

                //используя бизнес правило посчитать процент прохождения маршрута
                $readyPercent = (new RealEducationRoutesOfUser(
                    $route->id,
                    $route->userDataId,
                    $route->routeId,
                    $route->lastPassPointId
                ))->readyPercent($allPointsAmount, $passedPointsAmount);
            } else {
                //если для этого маршрута вообще нет точек
                $readyPercent = 0;
            }

            //заполнение временного массива для текущего маршрута
            $resultTmpArray[$routeNum]['routeName'] = $refRouteName;
            $resultTmpArray[$routeNum]['routeId'] = $route->id;
            $resultTmpArray[$routeNum]['readyPercent'] = $readyPercent;

            $routeNum++;
        }

        //передать сформированный массив маршрутов с точками маршрутов, в выходное дто
        return array_map(
            function ($tmp) {
                if(isset($tmp['points'])) {
                    $tmpPoints = array_map(function ($point) {
                        return new PointDTO(
                            pass: $point['pass'],
                            examDate: $point['examDate'],
                            realRoutePointId: $point['realRoutePointId'],
                            routePointName: $point['routePointName']
                        );
                    },
                        $tmp['points']);
                } else {
                    $tmpPoints = [];
                }

                return new OutputDTO(
                    readyPercent: $tmp['readyPercent'],
                    points: $tmpPoints,
                    routeName: $tmp['routeName'],
                    routeId: $tmp['routeId']
                );
            },
            $resultTmpArray
        );
    }


}
