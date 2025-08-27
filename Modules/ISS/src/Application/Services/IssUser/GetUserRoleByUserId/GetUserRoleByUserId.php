<?php

namespace ISS\App\Application\Services\IssUser\GetUserRoleByUserId;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\GetUserRoleByUserId\InputDTO;
use ISS\App\Application\Services\IssUser\GetUserRoleByUserId\OutputDTO;

class GetUserRoleByUserId
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить роль пользователя ИОС по коду пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getIssUserRole(['id' => $inputData->issUserId]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("ISS UserRole Error: {$e->getMessage()}");
        }

        return new OutputDTO(roleId: $result['id'], roleName: $result['name']);
    }
}
