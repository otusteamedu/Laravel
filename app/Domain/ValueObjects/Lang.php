<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\NotValidItemDomainException;

class Lang 
{
    private string $lang;

    public function __construct(string $lang)
    {
        if (!in_array($lang, ['ru','en'])) {
            throw new NotValidItemDomainException("Неподдерживаемый язык: $lang");
        }
        $this->lang = $lang;
    }

    public function getValue(): string
    {
        return $this->lang;
    }
}
