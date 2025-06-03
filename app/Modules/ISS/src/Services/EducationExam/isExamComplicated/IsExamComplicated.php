<?php

namespace App\Modules\ISS\src\Services\EducationExam\isExamComplicated;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\OutputDTO;

class IsExamComplicated
{
    public EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Определение типа теста (простой или сложный)
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function isExamComplicated(InputDTO $inputData): OutputDTO
    {
        $result = $this->repository->complicatedQuestionsCount(['id' => $inputData->id]);

        if ($result['countOfComplicatedQuestions'] > 0) {
            return new OutputDTO(isExamComplicated: true);
        } else {
            return new OutputDTO(isExamComplicated: false);
        }
    }
}
