<?php

namespace App\Domain\ValueObjects\Photo;

use App\Domain\Exceptions\NotValidItemDomainException;

class PhotoUrl  
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('URL фотографии не может быть пустым.');
        }
        
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
