<?php

namespace App\Modules\ISS\src\Services\EducationRoutePoint;

use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use Illuminate\Support\Facades\DB;

/**
 * Сервис реализует функционал для работы с точками учебного маршрута (из справочника)
 *
 */

class EducationRoutePointService
{
    /**
     * Достать общие данные для точки обучающего маршрута
     * @param array $inputData код реальной точки обучающего маршрута $inputData['id'], код пользо-я ИОС $inputData['user_data_id']
     * @return array
     */
    /*public function getRealPointMainData(array $inputData): array
    {
        $result = [];

    //education_route(name) + education_route_point(name)
    // + real_education_route_point (exam_date, position) + real_education_routes_of_users (last_pass_point_id =>position)

    //select
    //    rerp.id as route_point_id,
    //    rerp.exam_date as exam_date,
    //                        -- rerp.position,
        //    er.name as route_name, -- er.id,
        //                       -- er.id as route_id,
        //    erp.name as point_name,
        //                       -- reru.last_pass_point_id,
        //                       -- rerp2.position,
        //    case when rerp2.position is null ||  rerp.position > rerp2.position
        //             then case when rerp.exam_date >= current_date
        //                           then 'wait'
        //                       else 'expired'
        //            end
        //         else 'passed'
        //        end exam_result
        //from real_education_route_points rerp
        //         left join education_routes er on er.id = rerp.route_id
        //         left join education_route_points erp on erp.id = rerp.route_point_id
        //         left join real_education_routes_of_users reru on reru.route_id = er.id
        //         left join real_education_route_points as rerp2 on rerp2.id = reru.last_pass_point_id
        //where rerp.id = 10
        //and reru.user_data_id = 1
    //
    //

        try {
            $educationPointData = RealEducationRoutePoint::select(
                'real_education_route_points.id as route_point_id',
                'real_education_route_points.exam_date as exam_date',
                'er.name as route_name',
                'erp.name as point_name',
                'rerp2.exam_date as last_passed_exam_date'
            )->addSelect(
                DB::raw('
                                case when rerp2.position is null ||
                                real_education_route_points.position > rerp2.position
                                then case when real_education_route_points.exam_date >= current_date
                                          then \'wait\'
                                          else \'expired\'
                                          end
                                else           \'passed\'
                                end exam_result
                ')
            )
                ->join('education_routes as er', 'real_education_route_points.route_id', '=', 'er.id', 'left')
                ->join('education_route_points as erp', 'real_education_route_points.route_point_id', '=', 'erp.id', 'left')
                ->join('real_education_routes_of_users as reru', 'er.id', '=', 'reru.route_id', 'left')
                ->join('real_education_route_points as rerp2', 'reru.last_pass_point_id', '=', 'rerp2.id', 'left')
                ->where('real_education_route_points.id', $inputData['id'])
                ->where('reru.user_data_id', '=', $inputData['user_data_id'])
                ->first();
        } catch (\Exception $e) {
            $educationPointData = null;
            //здесь нужна запись в лог
        }

        if ($educationPointData) {
            $result['routeName'] = $educationPointData->route_name;
            $result['pointName'] = $educationPointData->point_name;
            $result['examResult'] = $educationPointData->exam_result;
            $result['examDate'] = $educationPointData->exam_date;
            $result['userId'] = $inputData['user_data_id'];
        }

        return $result;
    }*/

    /**
     * Достать видео/пдф/текстовые файлы точки обучающего маршрута
     * @param array $inputData код реальной точки обучающего маршрута, тип обучающего материала
     * @return array
     */
    /*public function getFilesOfRealPointData(array $inputData): array
    {
        $result = [];
        $materialType = $inputData['material_type'];

        if (in_array($materialType, config('iss::iss.ALLOWED_EDUCATION_MATERIAL_TYPES'))) {
            $rerp = RealEducationRoutePoint::where('id', $inputData['id'])->first();
            $erp = $rerp->educationRoutePoint()->first();

            if (!is_null($erp)) {
                $result = $erp->educationMaterial()->where('material_type_id', function($q) use ($materialType) {
                    return $q->select('id')->from('education_material_types')->where('name', $materialType)->first()->id;
                })->pluck('file_path')->toArray();
            }
        }

        return $result;
    }*/

    /**
     * Редактировать точку обучающего маршрута (из справочника) (опционно только если успею)
     * @param array $inputData код реальной точки обучающего маршрута
     * @return array
     */
    public function updateRealPointData(array $inputData): string
    {
        $result = null;
        //education_route + education_materials + education_material_types + exam_questions + exam_answers
        return $result;
    }
}
