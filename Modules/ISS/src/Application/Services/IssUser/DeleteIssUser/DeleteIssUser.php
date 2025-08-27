<?php

namespace ISS\App\Application\Services\IssUser\DeleteIssUser;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\DeleteIssUser\InputDTO;
use ISS\App\Application\Services\IssUser\DeleteIssUser\OutputDTO;

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
            $result = $this->repository->deleteIssUser(
                [
                    'iss_user_id' => $inputData->issUserId
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [0];
        }

        if ($result[0] > 0) {
            return new OutputDTO(result: true);
        } else {
            return new OutputDTO(result: false);
        }
    }
}
