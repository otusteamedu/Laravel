<?php

namespace ISS\App\Domain\IssUser;

use ISS\App\Domain\IssUser\ValueObjects\Organization;
use ISS\App\Domain\IssUser\ValueObjects\PartOfFio;
use ISS\App\Domain\IssUser\ValueObjects\UserIssAvatarPath;
use ISS\App\Domain\IssUser\ValueObjects\WebToken;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var Id $id код пользователя ИОС
 * @var Id $userId код пользователя из основного приложения
 * @var Id $roleId код роли пользователя ИОС
 * @var string $userIssAvatarPath путь к файлу аватарки пользователя
 * @var string $organization название организации пользователя из основного приложения
 * @var string $name имя поьлзователя (загружается из основного приложения)
 * @var string $secondName отчество поьлзователя (загружается из основного приложения)
 * @var string $lastName фамилия поьлзователя (загружается из основного приложения)
 * @var string $webToken токен авторизации пользователя в модуле ИОС
 */

class UserData
{
    private int $id;
    private int $userId;
    private int $roleId;
    private string $userIssAvatarPath;
    private string $organization;
    private string $name;
    private string $secondName;
    private string $lastName;
    private string $webToken;

    public function __construct(
        int             $id,
        int             $userId,
        int             $roleId,
        string          $userIssAvatarPath,
        string          $organization,
        string          $name,
        string          $secondName,
        string          $lastName,
        string          $webToken
    )
    {
        $this->id = (new Id($id))->id;
        $this->userId = (new Id($userId))->id;
        $this->roleId = (new Id($roleId))->id;
        $this->userIssAvatarPath = (new UserIssAvatarPath ($userIssAvatarPath))->userIssAvatarPath;
        $this->organization = (new Organization ($organization))->organization;
        $this->name = (new PartOfFio($name))->partOfFIO;
        $this->secondName = (new PartOfFio($secondName))->partOfFIO;
        $this->lastName = (new PartOfFio($lastName))->partOfFIO;
        $this->webToken = (new WebToken($webToken))->webToken;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getUserIssAvatarPath(): string//UserIssAvatarPath
    {
        return $this->userIssAvatarPath;
    }

    public function getOrganization(): string//Organization
    {
        return $this->organization;
    }

    public function getName(): string//PartOfFio
    {
        return $this->name;
    }

    public function getSecondName(): string//PartOfFio
    {
        return $this->secondName;
    }

    public function getLastName(): string//PartOfFio
    {
        return $this->lastName;
    }

    public function getWebToken(): string//WebToken
    {
        return $this->webToken;
    }

    //мутаторы
    public function changeUserRole(int $roleId): void
    {
        $this->roleId = (new Id($roleId))->id;
    }

    public function changeOrganization(string $organization): void
    {
        $this->organization = (new Organization($organization))->organization;
    }

    //бизнес правила

    /**
     * Проверка что пользователь имеет права администратора
     * @param string $userRole
     * @param bool $isIssAdmin
     * @return bool
     */
    public static function isAdmin(string $userRole, bool $isIssAdmin): bool
    {
        if ($userRole === 'admin' || $isIssAdmin === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Проверка что пользователь имеет права менеджера
     * @param string $userRole
     * @return bool
     */
    public static function isManager(string $userRole): bool
    {
        if ($userRole === 'manager') {
            return true;
        } else {
            return false;
        }
    }
}
