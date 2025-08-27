<?php

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm;

use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\InputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\OutputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as currentUserDTO;
use ISS\App\Application\Services\IssUser\GetUserRoleByUserId\GetUserRoleByUserId;
use ISS\App\Application\Services\IssUser\GetUserRoleByUserId\InputDTO as roleDTO;
use ISS\App\Application\Services\IssUser\GetAllUsers\GetAllUsers;
use ISS\App\Application\Services\IssUser\GetAllUsers\InputDTO as getAllUsersDTO;
use ISS\App\Application\Services\IssUser\GetAllUsersByOrganization\GetAllUsersByOrganization;
use ISS\App\Application\Services\IssUser\GetAllUsersByOrganization\InputDTO as userByOrganizationDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\GetAllRealRoutesOFUserByUserId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId\InputDTO as reruInputDTO;
use ISS\App\Application\Services\EducationRoute\GetRouteById\GetRouteById;
use ISS\App\Application\Services\EducationRoute\GetRouteById\InputDTO as erInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as lppInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\GetAllRealRoutePointsByRouteId;
use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\InputDTO as rerpInputDTO;

use ISS\App\Domain\IssUser\UserData;
use ISS\App\Domain\RealEducationRoutePoint\RealEducationRoutePoint;
use ISS\App\Domain\RealEducationRoutesOfUsers\RealEducationRoutesOfUser;

class GetRouteReadyPercentForUsersOfFirm
{
    private GetUserData $getUserData;
    private GetUserRoleByUserId $getUserRoleByUserId;
    private GetAllUsers $getAllUsers;
    private GetAllUsersByOrganization $getAllUsersByOrganization;
    private GetAllRealRoutesOFUserByUserId $getAllRealRoutesOFUserByUserId;
    private GetRouteById $getRouteById;
    private GetRealRoutePointById $getRealRoutePointById;
    private GetAllRealRoutePointsByRouteId $getAllRealRoutePointsByRouteId;

    public function __construct(
        GetUserData                    $getUserData,
        GetUserRoleByUserId            $getUserRoleByUserId,
        GetAllUsersByOrganization      $getAllUsersByOrganization,
        GetAllUsers                    $getAllUsers,
        GetAllRealRoutesOFUserByUserId $getAllRealRoutesOFUserByUserId,
        GetRouteById                   $getRouteById,
        GetRealRoutePointById          $getRealRoutePointById,
        GetAllRealRoutePointsByRouteId $getAllRealRoutePointsByRouteId
    )
    {
        $this->getUserData = $getUserData;
        $this->getUserRoleByUserId = $getUserRoleByUserId;
        $this->getAllUsersByOrganization = $getAllUsersByOrganization;
        $this->getAllUsers = $getAllUsers;
        $this->getAllRealRoutesOFUserByUserId = $getAllRealRoutesOFUserByUserId;
        $this->getRouteById = $getRouteById;
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getAllRealRoutePointsByRouteId = $getAllRealRoutePointsByRouteId;
    }

    /**
     * Достать степень прохождения маршрутов обучения всеми сотрудниками фирмы для текущего менеджера или админа
     * @param InputDTO $inputData
     * @return OutputDTO[]
     *           [
     *                [
     *                'organization'=> '..',
     *                'employees'=> [
     *                               'fio1'=>['r1'=>%, 'r2'=>%, ],
     *                               'fio2'=>['r5'=>%, 'r6'=>%, 'r3'=>%],
     *                               ...]
     *               ],
     *              [ 'organization'=> '..', 'employees'=> [..] ],
     *              [ 'organization'=> '..', 'employees'=> [..] ],
     *              ...
     *          ]
     */
    public function __invoke(InputDTO $inputData): array
    {
        //извлекаем данные пользователя переданного в этот сервис (по его id)--getUserData
        $currentUser = ($this->getUserData)(new currentUserDTO(
            fieldName: 'id',
            fieldValue: $inputData->id
        ));

        //извлекаем роль этого пользователя из user_roles (используя его user_data.id)
        $currentUserRole = ($this->getUserRoleByUserId)(new roleDTO(issUserId: $inputData->id))->roleName;

        //бизнес правилом проверяем имеет ли он доступ ко всем польз-ям (если админ) или только к своей орг-и (если менеджер)
        $isAdmin = UserData::isAdmin($currentUserRole, $inputData->isIssAdmin);
        $isManager = UserData::isManager($currentUserRole);

        //Извлекаем из UD (id, name, l_name, s_name, organization)
        //для всех польз-ей если переданый админ
        // или только его организации если менеджер
        if ($isAdmin === true) {
            $users = ($this->getAllUsers)(new getAllUsersDTO());
        } elseif ($isManager === true) {
            $users = ($this->getAllUsersByOrganization)(new userByOrganizationDTO(organization: $currentUser->organization));
        } else {
            return [];
        }

        //создаем заготовку под массив результатов
        $result = [];          //итоговый массив результатов
        $tmpResult = [];       //промежуточный массив-заготовка

        $organizations = array_unique(array_map(
            function ($u) {
                return $u->organization;
            },
            $users->users
        ));

        foreach ($organizations as $organization) {
            $tmpResult[$organization] = ['organization' => $organization, 'employees' => []];
        }

        //для каждого пользователя
        foreach ($users->users as $user) {
            //достать все реальные маршруты пользователя reru по user_data_id
            $currentUserRoutes = ($this->getAllRealRoutesOFUserByUserId)(new reruInputDTO(issUserId: $user->id));

            $tmpRoutes = []; //массив маршрутов для текущего пользователя
            //для каждого реального маршрута
            foreach ($currentUserRoutes->routes as $route) {
                //достаем данные его справочного маршрута
                $refRoute = ($this->getRouteById)(new erInputDTO(id: $route->routeId));

                //достаем позицию в маршруте для его последней пройденной точки
                if (!is_null($route->lastPassPointId)) {
                    $lastPassPointPosition = ($this->getRealRoutePointById)(new lppInputDTO(id: $route->lastPassPointId))->position;
                } else {
                    $lastPassPointPosition = null;
                }

                //достаем данные для всех реальных точек этого маршрута
                $allRERPs = ($this->getAllRealRoutePointsByRouteId)(new rerpInputDTO($refRoute->routeId))->realPoints;

                //для каждой реальной точки проверяем пройдена она или нет (бизнес правилом из домена)
                $tmpRERPs = array_map(
                    function ($rerp) use ($lastPassPointPosition) {
                        return (new RealEducationRoutePoint(
                            $rerp->id,
                            $rerp->routePointId,
                            $rerp->routeId,
                            $rerp->examDate,
                            $rerp->position,
                        ))->isPassed($lastPassPointPosition);
                    },
                    $allRERPs
                );

                //считаем процент прохождения маршрута по бизнес-правилу из домена
                $pointsCount = count($tmpRERPs);
                $passedPointsCount = count(array_filter($tmpRERPs, function ($tmp) {
                    return $tmp;
                }));

                $routePassPercent = (new RealEducationRoutesOfUser(
                    $route->id,
                    $route->userDataId,
                    $route->routeId,
                    $route->lastPassPointId,
                ))->readyPercent($pointsCount, $passedPointsCount);

                //записываем маршрут и его процент во временный массив
                $tmpRoutes[$refRoute->routeName] = $routePassPercent;
            }

            //создаем в результирующем массиве, элемент для текущего пользователя
            $f = $user->lastName ? $user->lastName . ' ' : '';
            $i = $user->name ? $user->name . ' ' : '';
            $o = $user->secondName ?? '';
            $fio = $f . $i . $o;
            $tmpResult[$user->organization]['employees'][$fio] = $tmpRoutes;
        }

        //преобразуем многомерный массив в массив OutputDTO
        foreach ($tmpResult as $key => $value) {
            $result[] = new OutputDTO(
                organization: $value['organization'],
                employees: $value['employees']
            );
        }

        return $result;
    }
}
