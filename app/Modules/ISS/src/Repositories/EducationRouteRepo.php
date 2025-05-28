<?php

namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;


class EducationRouteRepo implements EducationRouteRepoInterface
{

    /**
     * Запрос БД получить все обучающие маршруты пользователя с данными о проценте их прохождения
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['user_data_id']
     * @return array ['route_id'=>, 'route_name'=>, 'ready_percent'=>,]
     */
    public function getUserRoutesWithPassPercent(array $inputData): array
    {
        //real_education_route_points --> (count(*) / count(pos<last_passed_point pos) )*100 ---> (readyPersent)
        //QUERY TO GET PERCENT + MAIN CHAIN DATA (route data main in web.php)
        //select
        //        t.id route_id, /*t.user_data_id as user_data_id,*/
        //        t1.name as route_name,
        //        /*count(t2.id) all_points,
        //        case when t.last_pass_point_id is null then 0
        //            else sum(case when t2.position <= tt2.position then 1 else 0 end)
        //            end passed,*/
        //        case when t.last_pass_point_id is null || count(t2.id) = 0 then 0
        //             else round(( sum(case when t2.position <= tt2.position then 1 else 0 end) / count(t2.id) )*100, 0)
        //            end  ready_percent
        //    from real_education_routes_of_users t
        //    join education_routes t1 on t1.id = t.route_id
        //    left join real_education_route_points t2 on t2.route_id = t1.id
        //    left join real_education_route_points tt2 on tt2.id = t.last_pass_point_id
        //    where t.user_data_id = 1
        //    group by t.id

        $userRoutes = RealEducationRoutesOfUser::select(
            'real_education_routes_of_users.id as route_id',
            'er.name as route_name'
        )
            ->addSelect(
                DB::raw('case when real_education_routes_of_users.last_pass_point_id is null || count(rerp.id) = 0 then 0
                               else
                                    round(
                                             (
                                                  sum(case when rerp.position <= rerp2.position then 1 else 0 end) /
                                                  count(rerp.id)
                                              )*100,
                                          0)
                               end  ready_percent
                ')
            )
            ->join('education_routes as er', 'real_education_routes_of_users.route_id', '=', 'er.id')
            ->join('real_education_route_points as rerp', 'er.id', '=', 'rerp.route_id', 'left')
            ->join(
                'real_education_route_points as rerp2',
                'real_education_routes_of_users.last_pass_point_id',
                '=',
                'rerp2.id',
                'left'
            )->where('real_education_routes_of_users.user_data_id', '=', $inputData['user_data_id'])
            ->groupBy('real_education_routes_of_users.id')->get()->toArray();

        return $userRoutes;
    }

    /**
     * Запрос БД получить данные для всех точек всех обучающих маршрутов пользователя
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['user_data_id']
     * @return array ['route_id'=>, 'real_route_point_id'=>, 'exam_date'=>, 'point_name'=>, 'pass'=> ]
     */
    public function getAllRoutePointsForUser(array $inputData): array
    {
        //QUERY TO GET DATA FOR ALL ROUTES POINTS FOR EVERY ROUTE-CHAIN
        //select
        //    t.id as route_id,
        //    /* t1.name as route_name, t1.id as er_id*/
        //    t2.id as real_route_point_id, t2.exam_date as exam_date, /*t2.position,*/
        //    /*tt2.position as lpp_position,*/
        //    case when tt2.position is null ||  t2.position > tt2.position
        //             then case when t2.exam_date >= current_date
        //                           then 'wait'
        //                       else 'expired'
        //            end
        //         else 'passed'
        //        end pass,
        //    t3.name as point_name
        //from real_education_routes_of_users t
        //         join education_routes t1 on t1.id = t.route_id
        //         left join real_education_route_points t2 on t2.route_id = t1.id
        //         left join real_education_route_points tt2 on tt2.id = t.last_pass_point_id
        //         left join education_route_points t3 on t3.id = t2.route_point_id
        //where t.user_data_id = 1
        //order by t2.position asc

        $userRoutePoints = RealEducationRoutesOfUser::select(
            'real_education_routes_of_users.id as route_id',
            'rerp.id as real_route_point_id',
            'rerp.exam_date as exam_date',
            'erp.name as point_name'
        )
            ->addSelect(
                DB::raw('
                              case when rerp2.position is null ||  rerp.position > rerp2.position
                              then
                                   case when rerp.exam_date >= current_date
                                   then  \'wait\'
                                   else  \'expired\'
                                   end
                              else       \'passed\'
                              end pass
                ')
            )
            ->join('education_routes as er', 'real_education_routes_of_users.route_id', '=', 'er.id')
            ->join('real_education_route_points as rerp', 'er.id', '=', 'rerp.route_id', 'left')
            ->join(
                'real_education_route_points as rerp2',
                'real_education_routes_of_users.last_pass_point_id',
                '=',
                'rerp2.id',
                'left'
            )
            ->join('education_route_points as erp', 'rerp.route_point_id', '=', 'erp.id', 'left')
            ->where('real_education_routes_of_users.user_data_id', '=', $inputData['user_data_id'])
            ->orderBy('rerp.position', 'asc')
            ->get()->toArray();

        return $userRoutePoints;
    }

    /**
     * Запрос БД получить степень прохождения маршрутов обучения всеми сотрудниками фирмы для текущего менеджера или админа
     * @param array $inputData
     *              код польз-я ИОС
     *                  $inputData['user_data_id']
     *              отметка что администратор ИОС
     *                  $inputData['is_iss_admin']
     * @return array [
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ...
     *               ]
     */
    public function getRouteReadyPercentForUsersOfFirm(array $inputData): array
    {
        //процент прохождения маршрута = (кол-во пройденных точек \ общее кол-во точек маршрута )*100
        //select
        //        t.user_data_id as user_data_id,
        //        /*t.id education_route_id,*/
        //        t3.organization as organization,
        //        t1.name as route_name,
        //        /*count(t2.id) all_points,*/
        //        case when t.last_pass_point_id is null || count(t2.id) = 0 then 0
        //             else round(( sum(case when t2.position <= tt2.position then 1 else 0 end) / count(t2.id) )*100, 0)
        //            end  ready_percent,
        //    concat(coalesce(t3.name, ''), ' ', coalesce(t3.second_name, ''), ' ', coalesce(t3.last_name, '')) as fio
        //    from real_education_routes_of_users t
        //    left join education_routes t1 on t1.id = t.route_id
        //    left join real_education_route_points t2 on t2.route_id = t1.id
        //    left join real_education_route_points tt2 on tt2.id = t.last_pass_point_id
        //    left join user_data t3 on t3.id = t.user_data_id
        //    join user_data tt3 on tt3.organization = t3.organization
        //    where (tt3.id = 1 or $inputData['isIssAdmin'])
        //    group by t.id
        //

        $issUserId = $inputData['user_data_id'];
        $isIssAdmin = $inputData['is_iss_admin'];

        $rawData = RealEducationRoutesOfUser::select(
            'real_education_routes_of_users.user_data_id as user_data_id',
            'ud.organization as organization',
            'er.name as route_name'
        )->addSelect(
            DB::raw('
                    case when real_education_routes_of_users.last_pass_point_id is null || count(rerp.id) = 0
                    then 0
                    else round(( sum(case when rerp.position <= rerp2.position then 1 else 0 end) / count(rerp.id) )*100, 0)
                    end  ready_percent
                ')
        )->addSelect(
            DB::raw('
                    concat(
                    coalesce(ud.name, \'\'),
                     \' \',
                     coalesce(ud.second_name, \'\'),
                      \' \',
                      coalesce(ud.last_name, \'\')
                      ) as fio
                ')
        )
            ->join('education_routes as er', 'real_education_routes_of_users.route_id', '=', 'er.id', 'left')
            ->join('real_education_route_points as rerp', 'er.id', '=', 'rerp.route_id', 'left')
            ->join('real_education_route_points as rerp2', 'real_education_routes_of_users.last_pass_point_id', '=', 'rerp2.id', 'left')
            ->join('user_data as ud', 'real_education_routes_of_users.user_data_id', '=', 'ud.id', 'left')
            ->join('user_data as ud2', 'ud.organization', '=', 'ud2.organization')
            ->when(
                $isIssAdmin,
                function ($q) { return $q; },
                function ($q) use ($issUserId) { return $q->where('ud2.id', $issUserId);}
            )
            ->groupBy('real_education_routes_of_users.id')->get()->toArray();

        return $rawData;
    }
}
