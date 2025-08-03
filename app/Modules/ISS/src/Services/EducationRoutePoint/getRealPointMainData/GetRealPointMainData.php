<?php

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData;

use App\Modules\ISS\src\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\InputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\OutputDTO;

class GetRealPointMainData
{
    private EducationRoutePointRepoInterface $repository;

    public function __construct(EducationRoutePointRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать общие данные для точки обучающего маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $educationPointData = $this->repository->getRealPointMainData(
                [
                    'id' => $inputData->id,
                    'user_data_id' => $inputData->userDataId
                ]
            );
        } catch (\Error | \Exception $e) {
            $educationPointData = null;
            //здесь нужна запись в лог
        }

        if ($educationPointData && !empty($educationPointData)) {

            $result = new OutputDTO(
                routePointId: $educationPointData['route_point_id'],
                examDate: $educationPointData['exam_date'],
                routeName: $educationPointData['route_name'],
                pointName: $educationPointData['point_name'],
                lastPassedExamDate: $educationPointData['last_passed_exam_date'],
                examResult: $educationPointData['exam_result'],
            );
        } else {
            $result = null;
        }

        return $result;
    }

}
