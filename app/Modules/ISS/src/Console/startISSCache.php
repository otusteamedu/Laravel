<?php

namespace App\Modules\ISS\src\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;
use App\Modules\ISS\src\Services\issUser\getAllManagers\GetAllManagers;
use App\Modules\ISS\src\Services\issUser\getAllManagers\InputDTO as managerDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\GetAllUsers;
use App\Modules\ISS\src\Services\issUser\getAllUsers\InputDTO as userDTO;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as userDataDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\GetAllEducationRoutesOfUserWithPoints;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\InputDTO as userRouteDTO;

class startISSCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * @param GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm сервис
     * @param GetAllManagers $getAllManagers сервис
     * @param GetAllUsers $getAllUsers сервис
     * @param GetUserData $getUserData сервис
     * @param GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints сервис
     */
    public function __construct(
        private GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm,
        private GetAllManagers $getAllManagers,
        private GetAllUsers $getAllUsers,
        private GetUserData $getUserData,
        private GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints,
    )
    {
        $this->signature = 'iss:cache ' . '{start : ' . __('iss::issCommands.cache.startCommand') . '}';
        parent::__construct();
        $this->description = __('iss::issCommands.cache.description');

        $this->getRouteReadyPercentForUsersOfFirm = $getRouteReadyPercentForUsersOfFirm;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $diagramDataService = $this->getRouteReadyPercentForUsersOfFirm;

        //подготовка кэша диаграмм для админов
        Cache::tags(['diagram', 'adminDiagrams'])->remember(
            'adminDiagrams',
            60*5,
            function () use ($diagramDataService) {
                //получаем данные из сервисов
                //данные для диаграмм о степени прохождения обучающих маршрутов сотрудниками разных фирм
                return $diagramDataService(
                    new diagramDTO(id: null, isIssAdmin: true)
                );
            }
        );

        //подготовка кэша диаграмм для менеджеров
        $managers = ($this->getAllManagers)(new managerDTO())->users;
        foreach ($managers as $manager) {
            Cache::tags(['diagram', 'managerDiagram'])->remember(
                'managerDiagram_' . $manager->id,
                60*60,
                function () use ($manager, $diagramDataService) {
                    return $diagramDataService(
                        new diagramDTO(id: $manager->id, isIssAdmin: false));
                }
            );
        }

        //подготовка кэша основных данных и данных маршрутов для пользователей ИОС
        $allUsers = ($this->getAllUsers)(new userDTO())->users;
        $getUserDataService = $this->getUserData;
        $getRoutesWithPointsService = $this->getAllEducationRoutesOfUserWithPoints;

        foreach ($allUsers as $user) {
            //основные данные пользователя ИОС
            Cache::tags(['userData', 'userDataMain'])->remember(
                'userDataMain_' . $user->id,
                60*60,
                function () use ($user, $getUserDataService) {
                    return $getUserDataService(new userDataDTO(fieldName: 'id', fieldValue: $user->id));
                }
            );

            //обучающие маршруты пользователя ИОС
            Cache::tags(['userData', 'userDataRoutes'])->remember(
                'userDataRoutes_' . $user->id,
                60*60,
                function () use ($user, $getRoutesWithPointsService) {
                    return $getRoutesWithPointsService(new userRouteDTO(id: $user->id));
                }
            );
        }
    }
}
