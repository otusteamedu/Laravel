<?php

namespace App\Modules\ISS\src\Http\Controllers;

use Illuminate\View\View;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as userDataDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\GetAllEducationRoutesOfUserWithPoints;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\InputDTO as userRouteDTO;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;

class IssUserPageController extends Controller
{

    /**
     * Контроллер страницы пользователя
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
        $issUserParameters = $getUserData->getUserData(new userDataDTO(fieldName: 'id', fieldValue: $issUserId));

        if (is_null($issUserParameters)) {
            abort(404, 'User not found');
        }

        //обучающие маршруты пользователя
        $issUserRoutes = $getAllEducationRoutesOfUserWithPoints->getAllEducationRoutesOfUserWithPoints(
            new userRouteDTO(id: $issUserId)
        );

        //данные для диаграммы о степени прохождения обучающих маршрутов для менеджера
        if ($issUserParameters->roleName == config('iss.ROLE_MANAGER')
            || $issUserParameters->roleName == config('iss.ROLE_ADMIN')) {
            $diagramsData = $getRouteReadyPercentForUsersOfFirm->getRouteReadyPercentForUsersOfFirm(
                new diagramDTO(id: $issUserId, isIssAdmin: false)
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
                    'examDate' => $point->examDate,
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
