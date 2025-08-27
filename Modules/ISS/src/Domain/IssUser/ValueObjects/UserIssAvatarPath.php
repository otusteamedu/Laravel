<?php

namespace ISS\App\Domain\IssUser\ValueObjects;

use InvalidArgumentException;

/**
 * @var string $userIssAvatarPath путь к файлу аватарки пользователя
 */

final readonly class UserIssAvatarPath
{
    public string $userIssAvatarPath;

    public function __construct(string $userIssAvatarPath)
    {
        if(!is_string($userIssAvatarPath) && !is_null($userIssAvatarPath)) {
            throw new InvalidArgumentException("User ISS avatar path must be string or null");
        }
        if(empty($userIssAvatarPath)) {
            throw new InvalidArgumentException("User ISS avatar path must not be empty string");
        }
        $this->userIssAvatarPath = $userIssAvatarPath;
    }
}
