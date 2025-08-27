<?php

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetCheckCodeByUserIdAndRealPointId;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetCheckCodeByUserIdAndRealPointId\InputDTO;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetCheckCodeByUserIdAndRealPointId\OutputDTO;

class GetCheckCodeByUserIdAndRealPointId
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Ищет одноразовый проверочный код преподавателя по кодам пользователя ИОС (экзамен которого проверяем)
     * и коду реальной точки маршрута (для которой сдается экзамен)
     * @param InputDTO $inputDTO
     * @return ?OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): ?OutputDTO
    {
        try {
            $result = $this->repository->getCheckCodeByUserIdAndRealPointId(
                [
                    'iss_user_id' => $inputDTO->issUserId,
                    'real_route_point_id' => $inputDTO->realRoutePointId,
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [];
        }

        if (!empty($result)) {
            return new OutputDTO(examCheckCode: $result['exam_check_code']);
        } else {
            return null;
        }
    }
}
