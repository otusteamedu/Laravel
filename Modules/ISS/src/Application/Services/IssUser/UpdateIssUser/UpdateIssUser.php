<?php

namespace ISS\App\Application\Services\IssUser\UpdateIssUser;

use Illuminate\Support\Facades\Storage;
use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\UpdateIssUser\InputDTO;
use ISS\App\Application\Services\IssUser\UpdateIssUser\OutputDTO;

class UpdateIssUser
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Обновить данные пользователя ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData)//: OutputDTO
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

        //собираем данные для обновления
        $dataToUpdate = [
            'id' => $inputData->id,
            'user_id' => $inputData->userId,
            'role_id' => $newUserRole['id'],
            'organization' => $inputData->organization,
            'name' => $inputData->name,
            'second_name' => $inputData->secondName,
            'last_name' => $inputData->lastName,
            'email' => $inputData->email,
        ];

        //проверяем есть ли файл авы в переданных данных, если есть сохраняем на диск и его имя пишем в массив данных для БД,
        //если нет то вообще не трогаем аватарку
        if (isset($inputData->avatarFile)) {
            //save file
            Storage::disk('iss')
                ->putFileAs(
                    'public\\',
                    $inputData->avatarFile,
                    'avatar_' . $inputData->id . '.' . $inputData->avatarFile->extension()
                );
            $avatarFileName = 'avatar_' . $inputData->id . '.' . $inputData->avatarFile->extension();

            //если передали файл то добавляем его к данным на обновление
            $dataToUpdate['user_iss_avatar_path'] = $avatarFileName;
        }

        //в репе ищем модель обновляем данными из подготовленного массива
        try {
            $result = $this->repository->updateIssUser($dataToUpdate);
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [false];
        }

        return new OutputDTO(result: $result[0]);
    }
}
