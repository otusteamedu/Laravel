<?php

namespace ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser;

use ISS\App\Application\Services\IssUser\DeleteIssUser\DeleteIssUser as lowLevelDeleteIssUser;
use ISS\App\Application\Services\IssUser\DeleteIssUser\InputDTO as lowLevelDeleteIssUserDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser\DeleteAllEducationRoutesOfIssUser;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser\InputDTO as delRoutesDTO;
use ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser\InputDTO;
use ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser\OutputDTO;

class DeleteIssUser
{
    private lowLevelDeleteIssUser $lowLevelDeleteIssUser;
    private DeleteAllEducationRoutesOfIssUser $deleteAllEducationRoutesOfIssUser;

    public function __construct(
        lowLevelDeleteIssUser $lowLevelDeleteIssUser,
        DeleteAllEducationRoutesOfIssUser $deleteAllEducationRoutesOfIssUser
    )
    {
        $this->lowLevelDeleteIssUser = $lowLevelDeleteIssUser;
        $this->deleteAllEducationRoutesOfIssUser = $deleteAllEducationRoutesOfIssUser;
    }

    /**
     * Удалить пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result1 = ($this->deleteAllEducationRoutesOfIssUser)(new delRoutesDTO(issUserId: $inputData->issUserId))
                ->operationResult;
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result1 = false;
        }

        try {
            $result2 = ($this->lowLevelDeleteIssUser)(new lowLevelDeleteIssUserDTO(issUserId: $inputData->issUserId));
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result2 = false;
        }

        if ($result1 === true && isset($result2->result) && $result2->result === true) {
            return new OutputDTO(result: true);
        } else {
            return new OutputDTO(result: false);
        }
    }
}
