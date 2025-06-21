<?php

namespace App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\OutputDTO;

class MarkExamPassedForUser
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Поставить отметку что экзамен для пользователя сдан для заданной точки учебного маршрута
     * (заносит номер точки в таблицу real_education_routes_of_users.last_pass_point_id)
     * @param InputDTO $inputDTO
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        try {
            $realRouteId = $this->repository->getRealRouteIdForRealPointBelongs(
                [
                    'id' => $inputDTO->realRoutePointId,
                    'iss_user_id' => $inputDTO->issUserId
                ]
            );

            $result = $this->repository->updateLastPassPoint(
                [
                    'point_id' => $inputDTO->realRoutePointId,
                    'id' => $realRouteId['reru_id']
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = false;
        }

        return new OutputDTO(result: $result);
    }
}
