<?php

namespace ISS\App\Application\Services\Teacher\GetTeacherByOrganization;

use ISS\App\Application\Services\Teacher\TeacherRepoInterface;
use ISS\App\Application\Services\Teacher\GetTeacherByOrganization\InputDTO;
use ISS\App\Application\Services\Teacher\GetTeacherByOrganization\TeacherDTO;
use ISS\App\Application\Services\Teacher\GetTeacherByOrganization\OutputDTO;

class GetTeacherByOrganization
{
    private TeacherRepoInterface $repository;

    public function __construct(TeacherRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить всех преподавателей, относящихся к заданной оршанизации
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository
                ->getAllTeachersByOrganization(['connected_organization' => $inputData->organization]);
        } catch (\Error | \Exception $e) {
            //запись влог
            throw new \Exception("repo error getAllTeachersByOrganization: {$e->getMessage()}", 500);
        }

        return new OutputDTO(
            teachers: array_map(
                function ($teacher) {
                    return new TeacherDTO(
                        id: $teacher['id'],
                        connectedOrganization: $teacher['connected_organization'],
                        teacherEmail: $teacher['teacher_email'],
                    );
                },
                $result
            )
        );
    }
}
