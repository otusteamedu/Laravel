<?php

namespace ISS\App\Application\Services\Exam\ExamCheckCode\DelCheckCode;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\ExamCheckCode\DelCheckCode\InputDTO;
use ISS\App\Application\Services\Exam\ExamCheckCode\DelCheckCode\OutputDTO;

class DelCheckCode
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
        try {
            $result = $this->repository
                ->delCheckCode(['exam_check_code' => $inputData->examCheckCode, 'soft_delete' => $inputData->softDelete]);
        } catch (\Error | \Exception $e) {
            //запись влог
            $result = false;
        }

        return new OutputDTO(result: $result);
    }

}
