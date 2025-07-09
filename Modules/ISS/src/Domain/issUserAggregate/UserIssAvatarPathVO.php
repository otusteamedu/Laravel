<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var string $userIssAvatarPath путь к файлу аватарки пользователя
 */

class UserIssAvatarPathVO
{
    private string $userIssAvatarPath;

    public function __construct(string $userIssAvatarPath)
    {
        if(!is_string($userIssAvatarPath) && !is_null($userIssAvatarPath)) {
            throw new Exception("User ISS avatar path must be string or null");
        }
        if(empty($userIssAvatarPath)) {
            throw new Exception("User ISS avatar path must not be empty string");
        }
        $this->userIssAvatarPath = $userIssAvatarPath;
    }
}
