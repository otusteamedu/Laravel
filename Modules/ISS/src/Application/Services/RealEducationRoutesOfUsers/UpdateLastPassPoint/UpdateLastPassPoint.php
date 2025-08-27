<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\UpdateLastPassPoint;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;

class UpdateLastPassPoint
{
    private RealEducationRoutesOfUsersRepoInterface $repository;

    public function __construct(RealEducationRoutesOfUsersRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Обновить код поседней пройденной точки реального обучающего маршрута пользователя ИОС
     * по коду реального маршрута с использованием заданного нового кода для реальной точки маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->updateLastPassPoint(['point_id' => $inputData->newLppId, 'id' => $inputData->reruId]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = false;
        }

        return new OutputDTO(operationResult: $result);
    }
}
