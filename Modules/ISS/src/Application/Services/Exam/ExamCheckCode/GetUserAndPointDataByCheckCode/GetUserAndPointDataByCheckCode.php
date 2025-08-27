<?php

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode\InputDTO;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode\OutputDTO;

class GetUserAndPointDataByCheckCode
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Ищет код пользователя ИОС (экзамен которого проверяем)
     * и код реальной точки маршрута (для которой сдается экзамен)
     * по одноразовому коду проверки преподавателя
     * @param InputDTO $inputDTO
     * @return ?OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): ?OutputDTO
    {
        try {
            $result = $this->repository->getUserAndPointDataByCheckCode(
                [
                    'exam_check_code' => $inputDTO->examCheckCode
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [];
        }

        if (!empty($result)) {
            return new OutputDTO(issUserId: $result['iss_user_id'], realRoutePointId: $result['real_route_point_id']);
        } else {
            return null;
        }
    }
}
