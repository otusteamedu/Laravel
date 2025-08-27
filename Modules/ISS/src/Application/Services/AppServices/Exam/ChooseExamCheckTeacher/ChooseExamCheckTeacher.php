<?php

namespace ISS\App\Application\Services\AppServices\Exam\ChooseExamCheckTeacher;

use ISS\App\Application\Services\AppServices\Exam\ChooseExamCheckTeacher\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\ChooseExamCheckTeacher\OutputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as importedDTO;
use ISS\App\Application\Services\Teacher\GetTeacherByOrganization\GetTeacherByOrganization;
use ISS\App\Application\Services\Teacher\GetTeacherByOrganization\InputDTO as teacherInputDTO;

class ChooseExamCheckTeacher
{
    private GetUserData $getUserData;
    private GetTeacherByOrganization $getTeacherByOrganization;

    public function __construct(
        GetUserData $getUserData,
        GetTeacherByOrganization $getTeacherByOrganization
    )
    {
        $this->getUserData = $getUserData;
        $this->getTeacherByOrganization = $getTeacherByOrganization;
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
                    ($this->getTeacherByOrganization)(new teacherInputDTO(organization: $organization))->teachers,
                    'teacherEmail'
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
