<?php

namespace App\Modules\ISS\src\Services\issUser\createIssUserWebToken;

use Illuminate\Support\Str;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\InputDTO;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\OutputDTO;

class CreateIssUserWebToken
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создать авторизационный жетон для модели пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        $token = Str::random(50);

        try {
            $result = $this->repository->setWebToken(
                [
                    'iss_user_id' => $inputData->issUserId,
                    'web_token' => $token
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [false];
        }

        if ($result[0]) {
            return new OutputDTO(issUserWebToken: $token);
        } else {
            return new OutputDTO();
        }
    }
}
