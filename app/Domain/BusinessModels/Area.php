<?php

namespace App\Domain\BusinessModels;

use App\Application\Helpers\LocaleHelper;

class Area extends BaseModel implements BusinessModelsInterface
{
    private string $name;
    private ?string $created_at;

    public function __construct(
        string $name, 
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName(string $name) 
    {
        $this->name = $name;
    }

    public function getCreatedAt(): string 
    {
        return $this->created_at;
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'name_' . LocaleHelper::getLocale() => $this->name,
            'created_at' => $this->created_at
        ];
    }

    public function toArrayForCreat(): array 
    {
        return [
            'name_' . LocaleHelper::getLocale() => $this->name,
        ];
    }
}
