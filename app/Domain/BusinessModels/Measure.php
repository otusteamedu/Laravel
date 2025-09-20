<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Measure\MeasureName;

class Measure extends BaseModel implements BusinessModelsInterface
{
    private MeasureName $name;
    private ?string $created_at;

    public function __construct(
        MeasureName $name, 
        Lang $lang,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
        $this->created_at = $created_at;
    }

    public function getName(): MeasureName 
    {
        return $this->name;
    }

    public function rename(MeasureName $newName): void 
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
            'name' => $this->getName(),
            'created_at' => $this->getCreatedAt(),
        ];
        return $array;
    }
}
