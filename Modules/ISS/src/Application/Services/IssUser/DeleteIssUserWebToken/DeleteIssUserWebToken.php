<?php

namespace ISS\App\Application\Services\IssUser\DeleteIssUserWebToken;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\DeleteIssUserWebToken\InputDTO;
use ISS\App\Application\Services\IssUser\DeleteIssUserWebToken\OutputDTO;

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
    public function __invoke(InputDTO $inputData): OutputDTO
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
