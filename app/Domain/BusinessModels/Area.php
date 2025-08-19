<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Area\AreaLang;
use App\Domain\ValueObjects\Area\AreaName;

class Area extends BaseModel implements BusinessModelsInterface
{
    private AreaName $name;
    private AreaLang $lang;
    private ?string $created_at;

    public function __construct(
        AreaName $name, 
        AreaLang $lang,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
        $this->created_at = $created_at;
    }

    public function getName() 
    {
        return $this->name->getValue();
    }

    public function getLang() 
    {
        return $this->lang->getValue();
    }

    public function rename(string $newName) 
    {
        if ($this->name->getValue() === $newName) {
            throw new NotValidItemDomainException("Новое название совпадает со старым");
        }
        $this->name->setValue($newName);
    }

    public function getCreatedAt(): string 
    {
        return $this->created_at;
    }

    public function toArray(): array 
    {
        if (is_null($this->getId())) {
            $array = [
                'name_' . $this->getLang() => $this->getName(),
            ];
        } else {
            $array = [
                'id' => $this->getId(),
                'name_' . ($lang ?? $this->getLang()) => $this->getName(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
