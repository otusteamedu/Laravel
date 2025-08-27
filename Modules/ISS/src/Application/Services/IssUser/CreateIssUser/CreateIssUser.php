<?php

namespace ISS\App\Application\Services\IssUser\CreateIssUser;

use Illuminate\Support\Facades\Storage;
use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\CreateIssUser\InputDTO;
use ISS\App\Application\Services\IssUser\CreateIssUser\OutputDTO;

class CreateIssUser
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создать нового пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        //ищем в репе роль по имени если есть норм если нет ошибка
        try {
            $newUserRole = $this->repository->findIssUserRoleByName(['name' => $inputData->roleName]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            $newUserRole = null;
        }

        if (is_null($newUserRole)) {
            return new OutputDTO(result: false);
        }

        //собираем данные для нового пользователя
        $dataToCreate = [
            //'id' => $inputData->id,
            'user_id' => $inputData->userId,
            'role_id' => $newUserRole['id'],
            'organization' => $inputData->organization,
            'name' => $inputData->name,
            'second_name' => $inputData->secondName,
            'last_name' => $inputData->lastName,
            'email' => $inputData->email,
        ];

        //Создаем пользователя с данными из подготовленного массива
        try {
            $newUser = $this->repository->createIssUser($dataToCreate);
        } catch (\Error | \Exception $e) {
            //запись в лог
            $newUser = [null];
        }

        //добавляем аватар
        $avatar = [true]; //если аватар не задаем то просто ставим его операцию как успешно завершенную
        if (isset($inputData->avatarFile) && isset($newUser['id'])) {
            //save file
            Storage::disk('iss')
                ->putFileAs(
                    'public\\',
                    $inputData->avatarFile,
                    'avatar_' . $newUser['id'] . '.' . $inputData->avatarFile->extension()
                );
            $avatarFileName = 'avatar_' . $newUser['id'] . '.' . $inputData->avatarFile->extension();

            //если передали файл то добавляем его к данным на обновление
            $dataToUpdate['id'] = $newUser['id'];
            $dataToUpdate['user_iss_avatar_path'] = $avatarFileName;

            //обращаемся в репозиторий и задаем аватар
            try {
                $avatar = $this->repository->updateIssUser($dataToUpdate);
            } catch (\Error | \Exception $e) {
                //запись в лог
                $avatar = [false];
            }
        }

        if (isset($newUser['id']) && $avatar[0]) {
            return new OutputDTO(result: true);
        } else {
            return new OutputDTO(result: false);
        }

    }
}
