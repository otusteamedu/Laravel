<?php

namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;

class EducationRoutePointRepo implements EducationRoutePointRepoInterface
{
    /**
     * Достать общие данные для точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута
     *                  $inputData['id'],
     *              код пользо-я ИОС
     *                  $inputData['user_data_id']
     * @return array
     *         ['route_point_id'=>, 'exam_date'=>, 'route_name'=>, 'point_name'=>, 'last_passed_exam_date'=>, 'exam_result'=>]
     */
    public function getRealPointMainData(array $inputData): array
    {
        //education_route(name) + education_route_point(name)
        // + real_education_route_point (exam_date, position) + real_education_routes_of_users (last_pass_point_id =>position)

        //select
        //    rerp.id as route_point_id,
        //    rerp.exam_date as exam_date,
        //                        /* rerp.position, */
        //    er.name as route_name, /* er.id, */
        //                       /* er.id as route_id, */
        //    erp.name as point_name,
        //                       /* reru.last_pass_point_id,
        //                       rerp2.position, */
        //    rerp2.exam_date as last_passed_exam_date
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
            $result = RealEducationRoutePoint::select(
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
                                          then \'' . config('iss.REAL_ROUTE_POINT_STATE.wait') . '\'
                                          else \'' . config('iss.REAL_ROUTE_POINT_STATE.expired') . '\'
                                          end
                                else           \'' . config('iss.REAL_ROUTE_POINT_STATE.passed') . '\'
                                end exam_result
                ')
            )
                ->join('education_routes as er', 'real_education_route_points.route_id', '=', 'er.id', 'left')
                ->join('education_route_points as erp', 'real_education_route_points.route_point_id', '=', 'erp.id', 'left')
                ->join('real_education_routes_of_users as reru', 'er.id', '=', 'reru.route_id', 'left')
                ->join('real_education_route_points as rerp2', 'reru.last_pass_point_id', '=', 'rerp2.id', 'left')
                ->where('real_education_route_points.id', $inputData['id'])
                ->where('reru.user_data_id', '=', $inputData['user_data_id'])
                ->first()->toArray();

            return $result;
    }

    /**
     * Достать видео/пдф/текстовые файлы точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута,
     *                  $inputData['id']
     *              тип обучающего материала
     *                  $inputData['material_type']
     * @return array [['title' =>'example1', 'file_path' => 'example\file\path\1'], [...], [],...]
     */
    public function getFilesOfRealPointData(array $inputData): array
    {
        $materialType = $inputData['material_type'];

        $rerp = RealEducationRoutePoint::where('id', $inputData['id'])->first();
        $erp = $rerp->educationRoutePoint()->first();

        $result = $erp->educationMaterial()->where('material_type_id', function($q) use ($materialType) {
            return $q->select('id')->from('education_material_types')->where('name', $materialType)->first()->id;
        })->get(['title', 'file_path'])->toArray();

        return $result;
    }
}
