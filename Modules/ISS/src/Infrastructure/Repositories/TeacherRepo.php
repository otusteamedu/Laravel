<?php

namespace ISS\App\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use ISS\App\Application\Services\Teacher\TeacherRepoInterface;
use ISS\App\Infrastructure\Models\Teacher;

class TeacherRepo implements TeacherRepoInterface
{
    /**
     * Запрос БД достать всех преподавателей
     * (по полю "организация")
     * @param array $inputData
     *        название организации
     *        $inputData['connected_organization']
     * @return array
     */
    public function getAllTeachersByOrganization(array $inputData): array
    {
        return Teacher::where('connected_organization', $inputData['connected_organization'])->get()->toArray();
    }
}
