<?php

namespace App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\OutputDTO;

class IsExamCanBePassed
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Проверить разрешено ли сдать экзамен для текущей точки маршрута
     * (если для предыдущей точки маршрута экзамен не сдан то для текущей точки маршрута сдать его нельзя,
     * если пытаемся сдать экзамен для уже пройденной точки маршрута -- также запретит,
     * разрешено сдать экзамен только для точки маршрута следующей за последней сданной точкой данного маршрута)
     * @param InputDTO $inputDTO
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        /*
         select t.route_point_id      as rerp_id
                    , t.position
                    , '_'
                    , t1.user_data_id       as user
                    , t1.last_pass_point_id as lpp
               from real_education_route_points t
               join real_education_route_points tt on tt.route_id = t.route_id
               left join real_education_routes_of_users t1 on t1.route_id = tt.route_id
               where tt.id = 3           -- ($realEducationRoutePointId)
                 and t1.user_data_id = 1 -- ($issUserId)
               order by t.position desc

select t.route_id from real_education_route_points t where id = 3 -- 1 routeId
select last_pass_point_id from real_education_routes_of_users where route_id = 1 and user_data_id = 1 -- 3 lppId
select t.position from real_education_route_points t where id = 3 -- 20 position
select id from real_education_route_points where position > 20 and route_id = 1 order by position asc limit 1 -- id=2
        */

        try {
            $realRouteId = ($this->repository
                ->getRealRouteIdByRealPointId(['id' => $inputDTO->realRoutePointId]))['route_id'];
            $lppId = ($this->repository
                ->getLPPid(['route_id' => $realRouteId, 'user_data_id' => $inputDTO->issUserId]))['lpp_id'];

            if (is_null($lppId)) {
                $validPointId = ($this->repository->getFirstRoutePoint(['route_id' => $realRouteId]))['id'];
            } else {
                $pllPosition = ($this->repository->getLPPposition(['id' => $lppId]))['position'];
                $validPointIdArr = $this->repository
                    ->getNextExamPoint(['position' => $pllPosition, 'route_id' => $realRouteId]);
                if (isset($validPointIdArr[0])) {
                    $validPointId = $validPointIdArr[0]['id'];
                } else {
                    $validPointId = null;
                }
            }

        } catch (\Error | \Exception $e) {
            //запись влог
            return new OutputDTO(grantPassExam: false);
        }

        if ( $validPointId == $inputDTO->realRoutePointId) {
            return new OutputDTO(grantPassExam: true);
        } else {
            return new OutputDTO(grantPassExam: false);
        }
    }
}
