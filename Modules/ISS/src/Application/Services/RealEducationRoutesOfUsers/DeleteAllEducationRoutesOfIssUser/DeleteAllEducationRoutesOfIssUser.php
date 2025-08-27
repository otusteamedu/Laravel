<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser\InputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser\OutputDTO;

class DeleteAllEducationRoutesOfIssUser
{
    private RealEducationRoutesOfUsersRepoInterface $repository;

    public function __construct(RealEducationRoutesOfUsersRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Удалить полностью все реальные маршруты пользователей ИОС
     * по коду пользователей ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->deleteEducationRoutesOfIssUser(['iss_user_id' => $inputData->issUserId]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error deleteEducationRoutesOfIssUser: {$e->getMessage()}");
        }

        return new OutputDTO(true);

    }
}
