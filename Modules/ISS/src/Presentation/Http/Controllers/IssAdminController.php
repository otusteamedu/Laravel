<?php

namespace ISS\App\Presentation\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;

/**
 * Контроллер для интерфейса администратора (работа с пользователями ИОС)
 * содержит:
 * - методы для отображения форм для всех действия администратора
 * - методы для выполнения этих действий
 */

class IssAdminController extends Controller
{
    /**
     * Отображение страницы администратора ИОС
     * @param GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm
     * @return View
     */
    public function adminPanel(
        GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm,
    ): View
    {
        $diagramsData = Cache::tags(['diagram', 'adminDiagrams'])->remember(
            'adminDiagrams',
            60*60,
            function () use ($getRouteReadyPercentForUsersOfFirm) {
                //получаем данные из сервисов
                //данные для диаграмм о степени прохождения обучающих маршрутов сотрудниками разных фирм
                return $getRouteReadyPercentForUsersOfFirm(
                    new diagramDTO(id: session('issUser')->issUserId, isIssAdmin: true)
                );
            }
        );

        //переводим в требуемый вид (там где необходимо)
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
            'iss::issAdminPage',
            [
                'userRole' => config('iss.ROLE_ADMIN'),
                'diagrams' => $diagrams
            ]
        );
    }
}
