<?php

namespace ISS\App\Application\Services\Teacher;

interface TeacherRepoInterface
{
    /**
     * Запрос БД достать всех преподавателей
     * (по полю "организация")
     * @param array $inputData
     *        название организации
     *        $inputData['connected_organization']
     * @return array
     */
    public function getAllTeachersByOrganization(array $inputData): array;
}
