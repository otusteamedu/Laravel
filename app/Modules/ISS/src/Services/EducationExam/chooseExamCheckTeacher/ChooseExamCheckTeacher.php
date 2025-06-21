<?php

namespace App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\OutputDTO;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as importedDTO;

class ChooseExamCheckTeacher
{
    private EducationExamRepoInterface $repository;
    private GetUserData $getUserData;

    public function __construct(
        EducationExamRepoInterface $repository,
        GetUserData $getUserData
    )
    {
        $this->repository = $repository;
        $this->getUserData = $getUserData;
    }

    /**
     * Выбрать преподавателя, который будет проверять экзамен для заданного пользователя ИОС
     * (проверяющий выбирается среди преподавателей, доступных для организации к которой относится пользователь ИОС)
     * @param InputDTO $inputDTO
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        //находим организацию, к которой относится пользователь ИОС, который сдает экзамен
        $userData = ($this->getUserData)(
                new importedDTO(fieldName: 'id', fieldValue: $inputDTO->issUserId, returnedFields: ['organization', 'id'])
            );

        if($userData) {
            $organization = $userData->organization;

            //находим преподавателей, относящихся к этой организации
            try {
                $mailArray = array_column(
                    $this->repository->getTeachersOfOrganization(['organization' => $organization]),
                    'teacher_email'
                );
            } catch (\Error | \Exception $e) {
                //запись в лог
                $mailArray = [];
            }

            //выбираем случайным образом преподавателя из списка доступных
            if (!empty($mailArray)) {
                $result = $mailArray[array_rand($mailArray)];
            } else {
                $result = null;
            }
        } else {
            $result = null;
        }

        return new OutputDTO(email: $result);
    }
}
