<?php

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetAllRERPsForExamDateCheck;

use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\GetRoutePointById;
use ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById\InputDTO as erpInputDTO;
use ISS\App\Application\Services\EducationRoute\GetRouteById\GetRouteById;
use ISS\App\Application\Services\EducationRoute\GetRouteById\InputDTO as erInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePoints\GetAllRealRoutePoints;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetAllRERPsForExamDateCheck\OutputDTO;

class GetAllRERPsForExamDateCheck
{
    private GetRoutePointById $getRoutePointById;
    private GetRouteById $getRouteById;
    private GetAllRealRoutePoints $getAllRealRoutePoints;

    public function __construct(
        GetRoutePointById $getRoutePointById,
        GetRouteById $getRouteById,
        GetAllRealRoutePoints $getAllRealRoutePoints
    )
    {
        $this->getRoutePointById = $getRoutePointById;
        $this->getRouteById = $getRouteById;
        $this->getAllRealRoutePoints = $getAllRealRoutePoints;
    }

    /**
     * Достать все реальные точки всех обучающих маршрутов с их справочными данными
     * для функционала проверки даты приближающегося экзамена по планировщику заданий
     * @return array<OutputDTO>
     * @throws \Exception
     */
    public function __invoke(): array
    {
        //получить все реальные точки всех обучающих маршрутов
        $rerps = ($this->getAllRealRoutePoints)();

        $result = [];
        //для каждой реальной точки
        foreach ($rerps as $rerp) {
            //получить название ее справочной точки маршрута
            $refPointName = ($this->getRoutePointById)(new erpInputDTO(id: $rerp->routePointId))->pointName;

            //получить название ее справочного маршрута
            $refRouteName = ($this->getRouteById)(new erInputDTO(id: $rerp->routeId))->routeName;

            //записать полученные данные в требуемый формат
            $result[] = new OutputDTO(
                routeId: $rerp->routeId,
                routeName: $refRouteName,
                pointName: $refPointName,
                examDate: $rerp->examDate
            );
        }

        return $result;
    }
}
