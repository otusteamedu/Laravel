<?php

namespace App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints;

use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\InputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\OutputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\PointDTO;

class GetAllEducationRoutesOfUserWithPoints
{
    public EducationRouteRepoInterface $repository;

    public function __construct(EducationRouteRepoInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Получить все обучающие маршруты пользователя с точками маршрутов
     * @param InputDTO $inputData
     * @return OutputDTO[]
     */
    public function getAllEducationRoutesOfUserWithPoints(InputDTO $inputData): array
    {
        $result = [];

        try {
            //все маршруты пользователя (с процентом выполнения)
            $userRoutes = $this->repository->getUserRoutesWithPassPercent(['user_data_id' => $inputData->id]);

            //все точки маршрутов пользователя
            $userRoutePoints = $this->repository->getAllRoutePointsForUser(['user_data_id' => $inputData->id]);
        } catch (\Error | \Exception $e) {
            $userRoutes = [];
            $userRoutePoints = [];
            //запись в лог
        }

        //создаем маршруты ползьвателя
        foreach ($userRoutes as $route) {
            $result[] = [
                'readyPercent' => $route['ready_percent'],
                'points' => [],
                'routeName' => $route['route_name'],
                'routeId' => $route['route_id'],
            ];
        }

        //запролняем точки маршрутов
        foreach ($userRoutePoints as $point) {
            //если точка фэйковая (есть ее id в last_pass_point но точка не присоединена к маршруту) то пропускаем ее
            if (is_null($point['real_route_point_id'])) continue;

            //ищем индекс элемента массива $result с route_id как у точки маршрута
            $resultElementId = array_search($point['route_id'], array_column($result, 'routeId'));

            if (is_numeric($resultElementId)) {
                $pointsCount = count($result[$resultElementId]['points']);

                $result[$resultElementId]['points'][strval($pointsCount)] = [ //strval($pointsCount) $point['real_route_point_id']
                    'pass' => $point['pass'],
                    'examDate' => $point['exam_date'],
                    'realRoutePointId' => $point['real_route_point_id'],
                    'routePointName' => $point['point_name']
                ];
            }
        }

        return array_map(
            function ($res) {

                $tmpPoints = [];
                foreach ($res['points'] as $point) {
                    $tmpPoints[] = new PointDTO(
                        pass: $point['pass'],
                        examDate: $point['examDate'],
                        realRoutePointId: $point['realRoutePointId'],
                        routePointName: $point['routePointName']
                    );
                }

                return new OutputDTO(
                    readyPercent: $res['readyPercent'],
                    points: $tmpPoints,
                    routeName: $res['routeName'],
                    routeId: $res['routeId']
                );
            },
            $result
        );
    }


}
