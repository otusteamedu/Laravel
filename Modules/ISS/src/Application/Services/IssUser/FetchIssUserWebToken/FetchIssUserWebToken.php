<?php

namespace ISS\App\Application\Services\IssUser\FetchIssUserWebToken;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\FetchIssUserWebToken\InputDTO;
use ISS\App\Application\Services\IssUser\FetchIssUserWebToken\OutputDTO;

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
    public function __invoke(InputDTO $inputData): OutputDTO
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
