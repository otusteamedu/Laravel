<?php

namespace App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm;

use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\InputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\OutputDTO;

class GetRouteReadyPercentForUsersOfFirm
{
    private EducationRouteRepoInterface $repository;

    public function __construct(EducationRouteRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать степень прохождения маршрутов обучения всеми сотрудниками фирмы для текущего менеджера или админа
     * @param InputDTO $inputData
     * @return OutputDTO[]
     *           [
     *                [
     *                'organization'=>,
     *                'employees'=> [
     *                               'fio1'=>['r1'=>%, 'r2'=>%, ],
     *                               'fio2'=>['r5'=>%, 'r6'=>%, 'r3'=>%],
     *                               ...]
     *               ],
     *              [ 'organization'=>, 'employees'=> [..] ],
     *              [ 'organization'=>, 'employees'=> [..] ],
     *              ...
     *          ]
     */
    public function __invoke(InputDTO $inputData): array
    {
        $result = [];

        try {
            $rawData = $this->repository->getRouteReadyPercentForUsersOfFirm(
                [
                    'user_data_id' => $inputData->id,
                    'is_iss_admin' => $inputData->isIssAdmin
                ]
            );
        } catch (\Error | \Exception $e) {
            $rawData = [];
            //запись в лог
        }

        //преобразуем данные к виду ['organization'=>, 'employees'=>['fio1'=>['r1'=>%, 'r2'=>%, ], 'fio2'=>['r5'=>%, 'r6'=>%], ..]]
        if (!empty($rawData)) {
            //создаем заготовку результирующего массива
            $organizations = array_unique(array_column($rawData, 'organization'));
            foreach ($organizations as $organization) {
                $tmpResult[] = ['organization' => $organization, 'employees' => []];
            }

            //проходим по строкам из БД и раскладываем их в подготовленный массив
            foreach($rawData as $currentRaw) {
                $resIndex = array_search($currentRaw['organization'], array_column($tmpResult, 'organization'));
                if (isset($tmpResult[$resIndex]['employees'][$currentRaw['fio']] )) {
                    $tmpResult[$resIndex]['employees'][$currentRaw['fio']][$currentRaw['route_name']] = $currentRaw['ready_percent'];
                } else {
                    $tmpResult[$resIndex]['employees'][$currentRaw['fio']] = [$currentRaw['route_name'] => $currentRaw['ready_percent']];
                }
            }
        } else {
            $tmpResult = [];
        }

        //преобразуем многомерный массив в массив OutputDTO
        foreach ($tmpResult as $item) {
            $result[] = new OutputDTO(
                organization: $item['organization'],
                employees: $item['employees']
            );
        }

        return $result;
    }


}
