<?php

namespace ISS\App\Presentation\Http\Controllers\AdminInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints\GetAllRoutePoints;
use ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints\InputDTO as getAllPointsInputDTO;
use ISS\App\Application\Services\AppServices\EducationRoutePoint\GetPointData\GetPointData;
use ISS\App\Application\Services\AppServices\EducationRoutePoint\GetPointData\InputDTO as getPointDataInputDTO;

/**
 * @var array $RoutePointLabels заголовки для колонок таблицы параметров справочных точек обучающего маршрута
 * (порядок параметров в методах контроллера должен соответствовать порядку этих заголовков)
 * @var array $errorMessages сообщения об ошибках валидации
 * @var array $validationRules правила валидации для запросов
 */

class RoutePointManageController
{
    private array $routePointLabels;
    private array $validationRules;
    private array $errorMessages;

    public function __construct()
    {
        $this->routePointLabels = [
            'id' => __('iss::issAdminPointCRUDInterface.pointId'),
            'pointName' => __('iss::issAdminPointCRUDInterface.pointName'),
            //'' => __('iss::issAdminPointCRUDInterface.'),
            //'' => __('iss::issAdminPointCRUDInterface.'),
            //'' => __('iss::issAdminPointCRUDInterface.'),
        ];

        $this->validationRules = [
            //'issPointId' => 'nullable|integer',
        ];

        $this->errorMessages = [
            //'issPointId.integer' => __('iss::issAdminPointCRUDInterface.validationErrors.mustBeInteger'),
            '.' => __('iss::issAdminPointCRUDInterface.validationErrors.'),
        ];
    }


    /**
     * Показать вс справочные точки учебных маршрутов ИОС.
     * маршрут GET iss/admin/RoutePointManage
     *
     * @param GetAllRoutePoints $getAllRoutePoints сервис, для извлечения данных всех справочных точек обучающих маршрутов
     */
    public function index(GetAllRoutePoints $getAllRoutePoints)
    {
        if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $issPoints = $getAllRoutePoints(new getAllPointsInputDTO());

        $i = 0; $pointParameters = [];
        if (is_array($issPoints->routePoints)) {
            foreach ($issPoints->routePoints as $issPoint) {
                $pointParameters[$i]['pointId'] = $issPoint->pointId;
                $pointParameters[$i]['pointName'] = $issPoint->pointName;
                $i++;
            }
            unset($i);
        }

        return view(
            'iss::adminInterface.routePointCRUD.issPointList',
            ['labels' => $this->routePointLabels, 'pointParameters' => $pointParameters, 'success' => $success]
        );
    }

    /**
     * Показать данные справочной точки маршрута ИОС
     * маршрут GET iss/admin/RoutePointManage/{RoutePointManage}
     */
    public function show(int $issPointId)
    {
        //not used
    }

    /**
     * Отобразить форму для создания справочной точки маршрута ИОС
     * маршрут GET iss/admin/RoutePointManage/create
     */
    public function create()
    { echo 'will CREATE';exit;
        /*if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $pointParameters = [
            'issPointId' => null,

        ];
        return view(
            'iss::adminInterface.routePointCRUD.issPointCreateOrEdit',
            [
                'action' => config('iss.ISS_USER_ACTION.create'),
                'labels' => $this->routePointLabels,
                'userParameters' => $pointParameters,
                'success' => $success
            ]
        );*/
    }

    /**
     * Создать
     * маршрут POST iss/admin/RoutePointManage
     * @param CreateRoutePoint $createRoutePoint сервис создания новой справочной точки обучающего маршрута
     */
    /*public function store(Request $request, CreateRoutePoint $createRoutePoint)
    {
        $validator = Validator::make($request->input(), $this->validationRules, $this->errorMessages);
        try {
            $validated = $validator->validated();
        } catch (\Error | \Exception $e) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $newRoutePoint = $createRoutePoint(new createRoutePointInputDTO(
            educationMaterialFiles: $request->file('educationMaterialFiles'),
            pointId: $validated['pointId'],//$request->input('pointId'),

        ));

        if ($newRoutePoint->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminPointCRUDInterface.createSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminPointCRUDInterface.createError')]);
        }
    }*/

    /**
     * Отобразить форму для редактирования точки обучающего маршрута
     * маршрут GET iss/admin/RoutePointManage/{RoutePointManage}/edit
     * @param GetPointData $getPointData сервис для извлечения данных справочной точки маршрута
     */
    public function edit(GetPointData $getPointData, int $issPointId)
    {
        if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $routePoint = $getPointData(new getPointDataInputDTO(id: $issPointId));

        $pointParameters = [];
        $pointParameters['pointId'] = $issPointId;

        $i = 0;
        foreach ($routePoint->questions as $question) {
            $pointParameters['questions'][$i]['num']= $i;
            $pointParameters['questions'][$i]['id']= $question->id;
            $pointParameters['questions'][$i]['questionName'] = $question->questionName;
            $pointParameters['questions'][$i]['questionText'] = $question->questionText;

            $j = 0;
            foreach ($question->answers as $answer) {
                $pointParameters['questions'][$i]['answers'][$j]['num'] = $j;
                $pointParameters['questions'][$i]['answers'][$j]['id'] = $answer->id;
                $pointParameters['questions'][$i]['answers'][$j]['answerName'] = $answer->answerName;
                $pointParameters['questions'][$i]['answers'][$j]['answerText'] = $answer->answerText;
                $pointParameters['questions'][$i]['answers'][$j]['questionId'] = $answer->questionId;
                $pointParameters['questions'][$i]['answers'][$j]['isRight'] = $answer->isRight;
                $j++;
            }
            $i++;
        }
//echo '<pre>'; var_dump($pointParameters);exit;

        //$routePoint->materials --- НА ОЧЕРЕДИ
        // ПОКА ОСТАНАВЛИВАЮ ЗДЕСЬ РАБОТУ НАД АДМИНКОЙ Т.К. НЕ УСПЕВАЮ!!!!

        return view(
            'iss::adminInterface.routePointCRUD.issRoutePointCreateOrEdit',
            [
                'action' => config('iss.ISS_USER_ACTION.edit'),
                'labels' => $this->routePointLabels,
                'pointParameters' => $pointParameters,
                'success' => $success
            ]
        );
    }

    /**
     * Обновить данные точки обучающего маршрута
     * маршрут PUT|PATCH iss/admin/RoutePointManage/{RoutePointManage}
     * @param UpdateRoutePoint $updateRoutePoint сервис обновления данных справочной точки обучающего маршрута
     */
    public function update(Request $request, int $issPointId)//, UpdateRoutePoint $updateRoutePoint)
    {echo '<pre>'; var_dump($request->input());exit;
        $validator = Validator::make($request->input(), $this->validationRules, $this->errorMessages);
        try {
            $validated = $validator->validated();
        } catch (\Error | \Exception $e) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $result = $updateRoutePoint(new updateRoutePointInputDTO(
            id: $issPointId,
            educationMaterialFiles: $request->file('educationMaterialFiles'),
        ));

        if ($result->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminPointCRUDInterface.updatedSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminPointCRUDInterface.updateError')]);
        }
    }

    /**
     * Удалить справочную точку обучающего маршрута
     * маршрут DELETE iss/admin/RoutePointManage/{RoutePointManage}
     * @param DeleteRoutePoint $deleteRoutePoint сервис удаления справочной точки обучающего маршрута ИОС
     */
    public function destroy(int $issPointId)//DeleteRoutePoint $deleteRoutePoint, int $issPointId)
    {
        echo 'will DELETE_' . $issPointId;exit;
        /*$result = $deleteRoutePoint(new deleteRoutePointInputDTO($issPointId));

        if ($result->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminPointCRUDInterface.deleteSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminPointCRUDInterface.deleteError')]);
        }*/
    }
}
