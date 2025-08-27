<?php

namespace ISS\App\Presentation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;
use ISS\App\Application\Services\IssUser\GetAllManagers\GetAllManagers;
use ISS\App\Application\Services\IssUser\GetAllManagers\InputDTO as managerDTO;
use ISS\App\Application\Services\IssUser\GetAllUsers\GetAllUsers;
use ISS\App\Application\Services\IssUser\GetAllUsers\InputDTO as userDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as userDataDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\GetAllEducationRoutesOfUserWithPoints;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints\InputDTO as userRouteDTO;

class IssCache extends Command
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

    /** @var GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm сервис ИОС (описание в сервисе) */
    private GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm;

    /** @var GetAllManagers $getAllManagers сервис ИОС (описание в сервисе) */
    private GetAllManagers $getAllManagers;

    /** @var GetAllUsers $getAllUsers сервис ИОС (описание в сервисе) */
    private GetAllUsers $getAllUsers;

    /** @var GetUserData $getUserData сервис ИОС (описание в сервисе) */
    private GetUserData $getUserData;

    /** @var GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints сервис ИОС (описание в сервисе) */
    private GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints;

    /**
     * @param GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm сервис
     * @param GetAllManagers $getAllManagers сервис
     * @param GetAllUsers $getAllUsers сервис
     * @param GetUserData $getUserData сервис
     * @param GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints сервис
     */
    public function __construct(
        GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm,
        GetAllManagers $getAllManagers,
        GetAllUsers $getAllUsers,
        GetUserData $getUserData,
        GetAllEducationRoutesOfUserWithPoints $getAllEducationRoutesOfUserWithPoints,
    )
    {
        $this->signature = 'iss2:cache '
           . '{--' . config('iss.ISS_COMMANDS.cache.actionHotStart') . ' : ' . __('iss::issCommands.cache.startCommand') . '} '
           . '{--' . config('iss.ISS_COMMANDS.cache.actionClear') . ' : ' . __('iss::issCommands.cache.clearCommand') . '}';
        parent::__construct();
        $this->description = __('iss::issCommands.cache.description');

        $this->getRouteReadyPercentForUsersOfFirm = $getRouteReadyPercentForUsersOfFirm;
        $this->getAllManagers = $getAllManagers;
        $this->getAllUsers = $getAllUsers;
        $this->getUserData = $getUserData;
        $this->getAllEducationRoutesOfUserWithPoints = $getAllEducationRoutesOfUserWithPoints;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = 0;
        if ($this->option(config('iss.ISS_COMMANDS.cache.actionHotStart'))) {
            $this->hotStartIssCache();
            $action = 1;
        }
        if ($this->option(config('iss.ISS_COMMANDS.cache.actionClear'))) {
            $this->clearIssCache();
            $action = 1;
        }

        if ($action === 0) {
            $this->warn(__('iss::issCommands.cache.noOptionsSet'));
        }
    }

    /**
     * Прогреть кэш для модуля ИОС
     */
    private function hotStartIssCache()
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

        $this->info('Cache hot started!');
    }

    public function clearIssCache()
    {
        $action = $this->choice(
            __('iss::issCommands.cache.choiceCacheClearMode'),
            [
                __('iss::issCommands.cache.clearAll'),
                __('iss::issCommands.cache.clearUserData'),
                __('iss::issCommands.cache.clearEducationPointData'),
                __('iss::issCommands.cache.clearDiagramsData'),
            ]
        );

        switch ($action) {
            case __('iss::issCommands.cache.clearAll'):
                    Cache::tags(['userData'])->flush();
                    Cache::tags(['diagram'])->flush();
                    Cache::tags(['pointData'])->flush();
                    break;
            case __('iss::issCommands.cache.clearUserData'):
                    Cache::tags(['userData'])->flush();
                    break;
            case __('iss::issCommands.cache.clearEducationPointData'):
                    Cache::tags(['pointData'])->flush();
                    break;
            case __('iss::issCommands.cache.clearDiagramsData'):
                    Cache::tags(['diagram'])->flush();
                    break;
            default:
                    $action = 'default(clear all cache)';
                    Cache::tags(['userData'])->flush();
                    Cache::tags(['diagram'])->flush();
                    Cache::tags(['pointData'])->flush();
                    break;
        }
        $this->line($action . ': ok');
    }
}
