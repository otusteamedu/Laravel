<?php

namespace ISS\App\Domain\IssUser\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $fioPart часть ФИО пользователя из основного приложения
 */

final readonly class PartOfFio
{
    public string|null $partOfFIO;

    public function __construct($partOfFIO)
    {
        if (empty($partOfFIO)) {
            throw new InvalidArgumentException("Every part of user FIO can not be empty");
        }
        $this->partOfFIO = $partOfFIO;
    }
}
