<?php

namespace App\Modules\ISS\src\Services\EducationExam\chooseCheckType;

use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\OutputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\IsExamComplicated;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\InputDTO as importedDTO;

class ChooseCheckType
{
    private IsExamComplicated $isExamComplicated;

    public function __construct(IsExamComplicated $isExamComplicated)
    {
        $this->isExamComplicated = $isExamComplicated;
    }

    /**
     * Опеделить вид проверки теста (автоматическая или отправкой преподавателю)
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        //если все вопросы simple то авто, если хоть один не simple то преподу
        if ((($this->isExamComplicated)(new importedDTO($inputData->id)))->isComplicated) {
            return new OutputDTO(checkType: config('iss.EXAM_CHECK_TYPE.manual'));
        } else {
            return new OutputDTO(checkType: config('iss.EXAM_CHECK_TYPE.auto'));
        }
    }
}
