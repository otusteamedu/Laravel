<?php

namespace ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify;

//use App\Modules\ISS\src\Services\NotifyService\NotifyServiceRepoInterface;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\InputDTO;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\OutputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\GetRealPointMainData;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\InputDTO as rerpInputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as userDataInputDTO;

class GetDataForExamStatusNotify
{
    //private NotifyServiceRepoInterface $repository;
    private GetRealPointMainData $getRealPointMainData;
    private GetUserData $userData;

    public function __construct(
        //NotifyServiceRepoInterface $repository,
        GetRealPointMainData $getRealPointMainData,
        GetUserData $userData,
    )
    {
        //$this->repository = $repository;
        $this->getRealPointMainData = $getRealPointMainData;
        $this->userData = $userData;
    }

    /**
     * Получение данных для бланка оповещения о статусе его экзамена (пользователя ИОС)
     * @param InputDTO $inputDTO
     * @return ?OutputDTO
     */
    public function __invoke(InputDTO $inputDTO): ?OutputDTO
    {
        $examMainData = ($this->getRealPointMainData)(
            new rerpInputDTO(userDataId: $inputDTO->issUserId, id: $inputDTO->realRoutePointId)
        );

        $userData = ($this->userData)(
            new userDataInputDTO(fieldName: 'id', fieldValue: $inputDTO->issUserId, returnedFields: ['email'])
        );

        if($userData && $examMainData){
            return new OutputDTO(
                userEmail: $userData->email,
                routeName: $examMainData->routeName,
                pointName: $examMainData->pointName,
                examData: date('Y-m-d', strtotime($examMainData->examDate)),
            );
        } else {
            return null;
        }
    }
}
