<?php

namespace App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\InputDTO;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\OutputDTO;

class FetchIssUserWebToken
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
    public function fetchIssUserWebToken(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->fetchWebToken(
                [
                    'iss_user_id' => $inputData->issUserId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = ['web_token' => null];
        }

        return new OutputDTO($result['web_token']);
    }
}
