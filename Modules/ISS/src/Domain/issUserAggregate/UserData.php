<?php

namespace Modules\ISS\Domain\issUserAggregate;

use Modules\ISS\Domain\issUserAggregate\IdVO;
use Modules\ISS\Domain\issUserAggregate\UserIssAvatarPathVO;
use Modules\ISS\Domain\issUserAggregate\OrganizationVO;
use Modules\ISS\Domain\issUserAggregate\PartOfFioVO;
use Modules\ISS\Domain\issUserAggregate\WebTokenVO;

/**
 * @var IdVO $id код пользователя ИОС
 * @var IdVO $userId код пользователя из основного приложения
 * @var IdVO $roleId код роли пользователя ИОС
 * @var UserIssAvatarPathVO $userIssAvatarPath путь к файлу аватарки пользователя
 * @var OrganizationVO $organization название организации пользователя из основного приложения
 * @var PartOfFioVO $name имя поьлзователя (загружается из основного приложения)
 * @var PartOfFioVO $secondName отчество поьлзователя (загружается из основного приложения)
 * @var PartOfFioVO $lastName фамилия поьлзователя (загружается из основного приложения)
 * @var WebTokenVO $webToken токен авторизации пользователя в модуле ИОС
 */

class UserData
{
    private IdVO $id;
    private IdVO $userId;
    private IdVO $roleId;
    private UserIssAvatarPathVO $userIssAvatarPath;
    private OrganizationVO $organization;
    private PartOfFioVO $name;
    private PartOfFioVO $secondName;
    private PartOfFioVO $lastName;
    private WebTokenVO $webToken;

    public function __construct(
        IdVO                $id,
        IdVO                $userId,
        IdVO                $roleId,
        UserIssAvatarPathVO $userIssAvatarPath,
        OrganizationVO      $organization,
        PartOfFioVO         $name,
        PartOfFioVO         $secondName,
        PartOfFioVO         $lastName,
        WebTokenVO          $webToken
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
}
