<?php

namespace App\Modules\ISS\src\Services\issUser\deleteIssUser;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\deleteIssUser\InputDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUser\OutputDTO;

class DeleteIssUser
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Удалить пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result1 = $this->repository->deleteEducationRoutesOfIssUser(
                [
                    'iss_user_id' => $inputData->issUserId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result1 = [-1];
        }

        try {
            $result2 = $this->repository->deleteIssUser(
                [
                    'iss_user_id' => $inputData->issUserId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result2 = [-1];
        }

        if ($result1[0] >= 0 && $result2[0] > 0) {
            return new OutputDTO(result: true);
        } else {
            return new OutputDTO(result: false);
        }
    }
}
