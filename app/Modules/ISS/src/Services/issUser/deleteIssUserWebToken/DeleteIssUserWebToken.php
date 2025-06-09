<?php

namespace App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\InputDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\OutputDTO;

class DeleteIssUserWebToken
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Удалить авторизационный жетон для модели пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function deleteIssUserWebToken(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->delWebToken(
                [
                    'iss_user_id' => $inputData->issUserId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [false];
        }

        return new OutputDTO($result[0]);
    }
}
