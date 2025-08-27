<?php

namespace ISS\App\Presentation\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as userDataDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\GetAllEducationRoutesOfUserWithPoints;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\InputDTO as userRouteDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;

/**
 * Контроллер страницы пользователя ИОС
 * содержит:
 * - метод для отображения страницы
 */

class IssUserPageController extends Controller
{

    /**
     * Отображение страницы пользователя
     * @param GetUserData $getUserData
     * @param GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints
     * @param GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm
     * @param int $issUserId
     * @return View
     */
    public function userAccount(
        GetUserData                           $getUserData,
        GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints,
        GetRouteReadyPercentForUsersOfFirm    $getRouteReadyPercentForUsersOfFirm,
        int                                   $issUserId
    ): View
    {
        //получаем данные из сервисов
        //основные данные пользователя
        $issUserParameters = Cache::tags(['userData', 'userDataMain'])->remember(
            'userDataMain_' . $issUserId,
            60*60,
            function () use ($issUserId, $getUserData) {
                return $getUserData(new userDataDTO(fieldName: 'id', fieldValue: $issUserId));
            }
        );

        if (is_null($issUserParameters)) {
            abort(404, 'User not found');
        }

        //обучающие маршруты пользователя
        $issUserRoutes = Cache::tags(['userData', 'userDataRoutes'])->remember(
            'userDataRoutes_' . $issUserId,
            60*60,
            function () use ($issUserId, $getAllEducationRoutesOfUserWithPoints) {
                return $getAllEducationRoutesOfUserWithPoints(new userRouteDTO(id: $issUserId));
            }
        );

        //данные для диаграммы о степени прохождения обучающих маршрутов для менеджера
        if ($issUserParameters->roleName == config('iss.ROLE_MANAGER')
            || $issUserParameters->roleName == config('iss.ROLE_ADMIN')) {
            $diagramsData = Cache::tags(['diagram', 'managerDiagram'])->remember(
                'managerDiagram_' . $issUserId,
                60*60,
                function () use ($issUserId, $getRouteReadyPercentForUsersOfFirm) {
                    return $getRouteReadyPercentForUsersOfFirm(
                        new diagramDTO(id: $issUserId, isIssAdmin: false));
                }
            );
        } else {
            $diagramsData = [];
        }


        //переводим в требуемый вид (там где необходимо)
        //учебные маршруты с точками маршрутов
        $routes = [];
        foreach ($issUserRoutes as $route) {
            $routes[] = [
                'readyPercent' => $route->readyPercent,
                'points' => [],
                'routeName' => $route->routeName,
                'routeId' => $route->routeId,
            ];

            foreach ($route->points as $point) {
                $routes[count($routes) - 1]['points'][] = [
                    'pass' => $point->pass,
                    'examDate' => date('Y-m-d', strtotime($point->examDate)),
                    'realRoutePointId' => $point->realRoutePointId,
                    'routePointName' => $point->routePointName,
                ];
            }
        }

        //данные диаграмм
        $diagrams = [];
        foreach ($diagramsData as $currentDiagram) {
            $diagrams[$currentDiagram->organization] = [
                'json' => '{',//json_encode($currentDiagram->employees),
                'diagramName' => $currentDiagram->organization,
            ];

            foreach ($currentDiagram->employees as $fio => $item) {
                $diagrams[$currentDiagram->organization]['json'] .=
                    '"'.$fio.'": ['.json_encode($item).'], ';
            }
            $diagrams[$currentDiagram->organization]['json'] =
                rtrim($diagrams[$currentDiagram->organization]['json'], ', ');
            $diagrams[$currentDiagram->organization]['json'] .= '}';

        }

        return view(
            'iss::issUserPage',
            [
                'issUserId' => $issUserId,
                'userRole' => $issUserParameters->roleName,
                'userParameters' => [
                    'userAvatar' => $issUserParameters->avatarFilePath,
                    'Organization' => $issUserParameters->organization,
                    'Name' => $issUserParameters->name,
                    'SecondName' => $issUserParameters->secondName,
                    'LastName' => $issUserParameters->lastName,
                ],
                'routes' => $routes,
                'diagrams' => $diagrams
            ]
        );
    }
}
