<?php

namespace App\Domain\Repositories\User\Contracts;

use App\Domain\Repositories\User\DTO\UserDTO;
use App\Domain\Repositories\User\DTO\UserCreateDTO;
use App\Domain\Repositories\User\DTO\UserProfileDTO;
use App\Domain\Repositories\Common\FetchOptions;
use App\Domain\Repositories\User\DTO\UserSocialiteDTO;

interface UserRepositoryInterface
{
    /**
     * Получить пользователей
     * @param FetchOptions $options
     * @return UserDTO[]|null
     */
    public function fetch(FetchOptions $options): ?array;

    /**
     * Получить пользователя по id
     * @param int $id
     * @param bool|null $withProfile
     * @return UserDTO|null
     */
    public function find(int $id, bool $withProfile = false): ?UserDTO;

    /**
     * Добавить пользователя
     * @param UserCreateDTO $user
     * @return int
     */
    public function add(UserCreateDTO $user): int;

    /**
     * Обновить профиль пользователя
     * @param UserDTO $user
     * @return bool
     */
    public function save(UserDTO $user): bool;

    /**
     * Получить пользователя по email
     * @param string $email
     * @param bool $verified
     * @return UserDTO|null
     */
    public function findByEmail(string $email, bool $verified = false): ?UserDTO;

    /**
     * Обновить или создать профиль пользователя
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function saveProfile(UserProfileDTO $userProfile): int;

    /**
     * Обновить пароль пользователя
     * @param int $userId
     * @param string $password
     * @return bool
     */
    public function passwordUpdate(int $userId, string $password): bool;

    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialiteId
     * @param string $driver
     * @return UserDTO|null
     */
    public function socialiteFind(string $id, string $driver): ?UserDTO;

    /**
     * Добавить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return int
     */
    public function socialiteAdd(UserSocialiteDTO $userSocialite): int;

    /**
     * Обновить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return bool
     */
    public function socialiteSave(UserSocialiteDTO $userSocialite): bool;

    /**
     * Удалить привязку социальной сети у пользователя
     * @param int $is
     * @return bool
     */
    public function socialiteDestroy(int $id): bool;
}
