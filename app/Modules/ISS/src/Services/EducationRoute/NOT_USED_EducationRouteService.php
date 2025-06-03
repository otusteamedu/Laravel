<?php

namespace App\Modules\ISS\src\Services\EducationRoute;

use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use Illuminate\Support\Facades\DB;

/**
 * Сервис реализует функционал работы с учебными маршрутами
 * (если успею) ---- в том числе инструмент для создания и редактирования учебных маршрутов
 */

class EducationRouteService
{
    /**
     * Достать все обучающие маршруты пользователя с точками маршрутов
     * @param array $inputData код пользователя ИОС $inputData['user_data_id']
     * @return array [
     *                    [
     *                    'readyPercent' =>,
     *                    'points' => ['pass' =>, 'examDate' =>, 'realRoutePointId' =>, 'routePointName' => ],
     *                    'routeName' =>,
     *                    'routeId' =>
     *                     ],
     *                    [..],
     *                ]
     */
    /*public function getAllEducationRoutesOfUserWithPoints(array $inputData): array
    {
        $result = [];
        //education_routes + real_education_routes_of_users + real_education_route_points + education_route_points
        //
        //real_education_routes_of_users --> id (route_id), route_id, last_pass_point_id
        //education_routes --> name (routeName)
        //real_education_route_points --> id (realRoutePointId), exam_date, position
        //
        //education_route_points --> name (routePointName)
        //
        //
        //
        //
        //real_education_route_points --> (count(*) / count(pos<last_passed_point pos) )*100 ---> (readyPersent)
        //QUERY TO GET PERCENT + MAIN CHAIN DATA (route data main in web.php)
        //select
        //        t.id route_id,  --t.user_data_id as user_data_id,
        //        t1.name as route_name,
        //        --count(t2.id) all_points,
        //        --case when t.last_pass_point_id is null then 0
        //        --    else sum(case when t2.position <= tt2.position then 1 else 0 end)
        //        --    end passed,
        //        case when t.last_pass_point_id is null || count(t2.id) = 0 then 0
        //             else round(( sum(case when t2.position <= tt2.position then 1 else 0 end) / count(t2.id) )*100, 0)
        //            end  ready_percent
        //    from real_education_routes_of_users t
        //    join education_routes t1 on t1.id = t.route_id
        //    left join real_education_route_points t2 on t2.route_id = t1.id
        //    left join real_education_route_points tt2 on tt2.id = t.last_pass_point_id
        //    where t.user_data_id = 1
        //    group by t.id
        //
        //
        //
        //
        //прохождение точки real_education_route_points логика
        //id(position) < last_pass_point_id(position)  ===> passed
        //(id(position) > last_pass_point_id(position) || last_pass_point_id is null) && exam_date < current_date  ===> expired
        //(id(position) > last_pass_point_id(position) || last_pass_point_id is null) && exam_date > current_date  ===> wait


        //QUERY TO GET DATA FOR ALL ROUTES POINTS FOR EVERY ROUTE-CHAIN
        //select
        //    t.id as route_id,
        //    --t1.name as route_name, t1.id as er_id
        //    t2.id as real_route_point_id, t2.exam_date as exam_date, --t2.position,
        //    --tt2.position as lpp_position,
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
        //

        //все маршруты пользователя (с процентом выполнения)
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
            ->groupBy('real_education_routes_of_users.id')->get();

        //все точки маршрутов пользователя
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
            ->get();


        //создаем маршруты ползьвателя
        foreach ($userRoutes as $route) {
            $result[] = [
                'readyPercent' => $route->ready_percent,
                'points' => [],
                'routeName' => $route->route_name,
                'routeId' => $route->route_id,
            ];
        }

        //запролняем точки маршрутов
        foreach ($userRoutePoints as $point) {
            //ищем индекс элемента массива $result с route_id как у точки маршрута
            $resultElementId = array_search($point->route_id, array_column($result, 'route_id'));

            if ($resultElementId) {
                $pointsCount = count($result[$resultElementId]['points']);

                $result[$resultElementId]['points'][strval($pointsCount + 1)] = [
                    'pass' => $point->pass,
                    'examDate' => $point->exam_date,
                    'realRoutePointId' => $point->real_route_point_id,
                    'routePointName' => $point->point_name
                ];
            }
        }

        return $result;
    }*/

    /**
     * Достать степень прохождения маршрутов обучения всеми сотрудниками фирмы для текущего менеджера или админа
     * @param array $inputData код польз-я ИОС $inputData['user_data_id']
     * @return array ['organization'=>, 'employees'=>['fio1'=>['r1'=>%, 'r2'=>%, ], 'fio2'=>['r5'=>%, 'r6'=>%, 'r3'=>%], ...]]
     */
    /*public function getRouteReadyPercentForUsersOfFirm(array $inputData): array
    {
        $result = [];

        //процент прохождения маршрута = (кол-во пройденных точек \ общее кол-во точек маршрута )*100
        //select
        //        t.user_data_id as user_data_id,
        //        --t.id education_route_id,
        //        t3.organization as organization,
        //        t1.name as route_name,
        //        --count(t2.id) all_points,
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
        //    where tt3.id = 1
        //    group by t.id
        //

        //получаем данные из БД в виде [['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>], [..], ]
        try {
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
                ->where('ud2.id', $inputData['user_data_id'])
                ->groupBy('real_education_routes_of_users.id')->get()->toArray();
        } catch (\Error | \Exception $e) {
            $rawData = [];
            //з\апись в лог
        }

        //преобразуем данные к виду ['organization'=>, 'employees'=>['fio1'=>['r1'=>%, 'r2'=>%, ], 'fio2'=>['r5'=>%, 'r6'=>%], ..]]
        if (!empty($rawData)) {
            $result['organization'] = $rawData[0]['organization'];
            $result['employees'] = [];
            foreach ($rawData as $row) {
                $result['employees'][$row['fio']][$row['route_name']] = $row['ready_percent'];
            }
        } else {
            $result = [];
        }

        return $result;
    }*/

    /**
     * Добавить точку обучающего маршрута (опционно, только если успею)
     * @param array $inputData код маршрута, дата экзамена, код точки из справочника
     * @return string
     */
    public function addEducationRoutePoint(array $inputData): string
    {
        $result = null;
        //real_education_route_points (exam date !)
        return $result;
    }

    /**
     * Удалить точку обучающего маршрута (опционно, только если успею)
     * @param array $inputData код точки маршрута
     * @return string
     */
    public function deldEducationRoutePoint(array $inputData): string
    {
        $result = null;
        //real_education_route_points (exam date !)
        return $result;
    }

    /**
     * Редактировать точку обучающего маршрута (опционно, только если успею)
     * @param array $inputData код точки маршрута
     * @return string
     */
    public function updateEducationRoutePoint(array $inputData): string
    {
        $result = null;
        //real_education_route_points (exam date !)
        return $result;
    }
}
