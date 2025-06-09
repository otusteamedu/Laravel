<?php

namespace App\Modules\ISS\src\Http\Controllers;

use Illuminate\View\View;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\InputDTO as diagramDTO;

class IssAdminController extends Controller
{
    /**
     * Контроллер страницы администратора ИОС
     * @param GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm
     * @return View
     */
    public function adminPanel(
        GetRouteReadyPercentForUsersOfFirm $getRouteReadyPercentForUsersOfFirm,
    ): View
    {
        //получаем данные из сервисов
        //данные для диаграмм о степени прохождения обучающих маршрутов сотрудниками разных фирм
        $diagramsData = $getRouteReadyPercentForUsersOfFirm->getRouteReadyPercentForUsersOfFirm(
            new diagramDTO(id: null, isIssAdmin: true)
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
