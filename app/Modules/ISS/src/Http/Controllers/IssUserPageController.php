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
        GetUserData $getUserData,
        GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints,
        GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm,
        int $issUserId
    ): View
    {
        /** @TODO пока нет авторизации здесь вручную ставлю код пользователя в сессию, потом переделаю как надо */
        request()->session()->remove('userId');
        session(['userId' => $issUserId]);

        //получаем данные из сервисов
        $issUserParameters = $getUserData->getUserData(new userDataDTO(issUserId: $issUserId));

        if (is_null($issUserParameters)) {
            abort(404, 'User not found');
        }

        $issUserRoutes = $getAllEducationRoutesOfUserWithPoints->getAllEducationRoutesOfUserWithPoints(
            new userRouteDTO(id: $issUserId)
        );
        /** @TODO вызов этого сервиса надо обернуть в гейт -- если менеджер - разрешен, если сотрудник - нет */
        /** @TODO сейчас вывод диаграммы запрещен для сотрудника (в шаблоне), но данные передаются во view - это плохо */
        $diagramsData = $getRouteReadyPercentForUsersOfFirm->getRouteReadyPercentForUsersOfFirm(
            new diagramDTO(id: $issUserId, isIssAdmin: $issUserParameters->roleName == 'admin' ? true : false)
        );


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
                'userRole' => $issUserParameters->roleName,
                'userParameters' => [
                    'userAvatar' => $issUserParameters->avatarFilePath,
                    'Organization' => $issUserParameters->organization,
                    'Name' => $issUserParameters->name,
                    'SecondName' => $issUserParameters->second_name,
                    'LastName' => $issUserParameters->last_name,
                ],
                'routes' => $routes,
                'diagrams' => $diagrams
            ]
        );
    }
}
