<?php

namespace ISS\App\Application\Services\RealEducationRoutePoint;

interface RealEducationRoutePointRepoInterface
{
    /**
     * Запрос БД получить данные реальной точки обучающего маршрута (по ее id)
     * @param array $inputData
     *                 код реальной точки маршрута
     *                     $inputData['id']
     *                 массив имен полей, которые хотим получить
     *                     $inputData['returned_fields']
     * @return array
     */
    public function getRealRoutePointById(array $inputData): array;

    /**
     * Запрос БД получить данные всех реальных точек реального обучающего маршрута (по коду этого маршрута)
     * @param array $inputData
     *                 код маршрута
     *                     $inputData['route_id']
     * @return array [['id'=>, 'route_point_id'=>, 'route_id'=>, 'exam_date'=>, 'position'=>, 'created_at'=>, ...], .... ]
     */
    public function getAllRealRoutePointsByRouteId(array $inputData): array;

    /**
     * Запрос БД получить данные всех реальных точек для всех обучающих маршрутов
     * @return array [['id'=>, 'route_point_id'=>, 'route_id'=>, 'exam_date'=>, 'position'=>, 'created_at'=>, ...], .... ]
     */
    public function getAllRealRoutePoints(): array;

    /**
     * Запрос БД получить данные первой (по порядку) точки на обучающем мершруте
     * @param array $inputData
     *               код маршрута пользователя
     *                   $inputData['route_id']
     * @return array ['id' => , 'route_point_id' => , ....]
     */
    public function getFirstRoutePoint(array $inputData): array;

    /**
     * Запрос БД получить данные реальной точки маршрута, следующей за указанной точкой
     * @param array $inputData
     *               позиция на маршруте последней пройденной точки
     *                   $inputData['position']
     *               код маршрута из справочника
     *                    $inputData['route_id']
     * @return array ['id'=> , 'route_point_id'=> , 'route_id'=> , 'position'=> , 'exam_date'=> , 'created_at'=> , .. ]
     */
    public function getNextRealRoutePointByPosition(array $inputData): array;
}
