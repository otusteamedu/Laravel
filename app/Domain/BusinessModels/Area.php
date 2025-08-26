<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Area\AreaName;

class Area extends BaseModel implements BusinessModelsInterface
{
    private AreaName $name;
    private ?string $created_at;

    public function __construct(
        AreaName $name, 
        Lang $lang,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
        $this->created_at = $created_at;
    }

    public function getName(): AreaName 
    {
        return $this->name;
    }

    public function rename(AreaName $newName): void 
    {
        if ($this->name === $newName) {
            throw new NotValidItemDomainException("Новое название совпадает со старым");
        }
        $this->name = $newName;
    }

    public function getCreatedAt(): string 
    {
        return $this->created_at;
    }

    public function toArray(): array 
    {
        $array = [
            'name' => $this->getName()->getValue(),
            'created_at' => $this->getCreatedAt(),
        ];
        return $array;
    }
}
