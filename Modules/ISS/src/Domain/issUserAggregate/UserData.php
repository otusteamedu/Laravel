<?php

namespace Modules\ISS\Domain\issUserAggregate;

use Modules\ISS\Domain\issUserAggregate\ValueObjects\Organization;
use Modules\ISS\Domain\issUserAggregate\ValueObjects\PartOfFio;
use Modules\ISS\Domain\issUserAggregate\ValueObjects\UserIssAvatarPath;
use Modules\ISS\Domain\issUserAggregate\ValueObjects\WebToken;
use Modules\ISS\Domain\SharedValueObjects\Id;

/**
 * @var Id $id код пользователя ИОС
 * @var Id $userId код пользователя из основного приложения
 * @var Id $roleId код роли пользователя ИОС
 * @var UserIssAvatarPath $userIssAvatarPath путь к файлу аватарки пользователя
 * @var Organization $organization название организации пользователя из основного приложения
 * @var PartOfFio $name имя поьлзователя (загружается из основного приложения)
 * @var PartOfFio $secondName отчество поьлзователя (загружается из основного приложения)
 * @var PartOfFio $lastName фамилия поьлзователя (загружается из основного приложения)
 * @var WebToken $webToken токен авторизации пользователя в модуле ИОС
 */

class UserData
{
    private Id $id;
    private Id $userId;
    private Id $roleId;
    private UserIssAvatarPath $userIssAvatarPath;
    private Organization $organization;
    private PartOfFio $name;
    private PartOfFio $secondName;
    private PartOfFio $lastName;
    private WebToken $webToken;

    public function __construct(
        Id                $id,
        Id                $userId,
        Id                $roleId,
        UserIssAvatarPath $userIssAvatarPath,
        Organization      $organization,
        PartOfFio         $name,
        PartOfFio         $secondName,
        PartOfFio         $lastName,
        WebToken          $webToken
    )
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->userIssAvatarPath = $userIssAvatarPath;
        $this->organization = $organization;
        $this->name = $name;
        $this->secondName = $secondName;
        $this->lastName = $lastName;
        $this->webToken = $webToken;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getRoleId(): Id
    {
        return $this->roleId;
    }

    public function getUserIssAvatarPath(): UserIssAvatarPath
    {
        return $this->userIssAvatarPath;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getName(): PartOfFio
    {
        return $this->name;
    }

    public function getSecondName(): PartOfFio
    {
        return $this->secondName;
    }

    public function getLastName(): PartOfFio
    {
        return $this->lastName;
    }

    public function getWebToken(): WebToken
    {
        return $this->webToken;
    }

    //мутаторы
    public function changeUserRole(int $roleId): void
    {
        $this->roleId = new Id($roleId);
    }

    public function changeOrganization(string $organization): void
    {
        $this->organization = new Organization($organization);
    }
}
