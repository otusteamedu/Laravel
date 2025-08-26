<?php

namespace App\Domain\ValueObjects\Video;

use App\Domain\Exceptions\NotValidItemDomainException;

class VideoPath 
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Путь до видео не может быть пустым.');
        }
        
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
