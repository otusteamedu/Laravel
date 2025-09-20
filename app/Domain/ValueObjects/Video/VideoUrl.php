<?php

namespace App\Domain\ValueObjects\Video;

use App\Domain\Exceptions\NotValidItemDomainException;

class VideoUrl  
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('URL видео не может быть пустым.');
        }
        
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
