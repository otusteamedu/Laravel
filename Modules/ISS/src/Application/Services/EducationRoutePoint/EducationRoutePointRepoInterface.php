<?php

namespace ISS\App\Application\Services\EducationRoutePoint;

interface EducationRoutePointRepoInterface
{
    /**
     * Запрос к БД достать общие данные для точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута
     *                  $inputData['id'],
     *              код пользо-я ИОС
     *                  $inputData['user_data_id']
     * @return array
     *         ['route_point_id'=>, 'exam_date'=>, 'route_name'=>, 'point_name'=>, 'last_passed_exam_date'=>, 'exam_result'=>]
 */
    public function getRealPointMainData(array $inputData): array;

    /**
     * Запос к БД достать видео/пдф/текстовые файлы точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута,
     *                  $inputData['id']
     *              тип обучающего материала
     *                  $inputData['material_type']
     * @return array [['title' =>'example1', 'file_path' => 'example\file\path\1'], [...], [],...]
     */
    public function getFilesOfRealPointData(array $inputData): array;

    /**
     * Запрос к БД достать все справочные точки обучающих маршрутов
     * @param array $inputData
     *              массив имен полей, которые хотим получить
     *              $inputData['returned_fields']
     * @return array
     */
    public function getAllReferenceRoutePoints(array $inputData): array;

    /**
     * Запрос к БД достать данные справочной точки маршрута по ее ID
     * @param array $inputData
     *              код спрравочной точки маршрута
     *              $inputData['id']
     * @return array ['id'=> , 'name'=> , 'created_at'=>, ...]
     */
    public function getRoutePointById(array $inputData): array;




}
