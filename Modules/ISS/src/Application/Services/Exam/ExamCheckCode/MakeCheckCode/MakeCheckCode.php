<?php

namespace ISS\App\Application\Services\Exam\ExamCheckCode\MakeCheckCode;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\ExamCheckCode\MakeCheckCode\InputDTO;
use ISS\App\Application\Services\Exam\ExamCheckCode\MakeCheckCode\OutputDTO;

class MakeCheckCode
{
    private EducationExamRepoInterface $repository;

    public function __construct(
        EducationExamRepoInterface $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * Удалить одноразовый проверочный код и его запись из БД
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        $examCheckCode = uniqid('exam_code_' . $inputData->issUserId . '_', true);
        try {
            $this->repository->makeCheckCode(
                [
                    'iss_user_id' => $inputData->issUserId,
                    'real_route_point_id' => $inputData->realRoutePointId,
                    'exam_check_code' => $examCheckCode,
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись влог
            $examCheckCode = null;
        }

        return new OutputDTO(examCheckCode: $examCheckCode);
    }

}
