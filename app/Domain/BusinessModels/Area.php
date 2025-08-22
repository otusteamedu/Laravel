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

    public function getName(): AreaName 
    {
        return $this->name;
    }

    public function getLang(): AreaLang 
    {
        return $this->lang;
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

    public function toArray(?string $lang = null): array 
    {
        if (is_null($this->getId())) {
            $array = [
                'name_' . $this->getLang()->getValue() => $this->getName()->getValue(),
            ];
        } else {
            $array = [
                'id' => $this->getId(),
                'name_' . ($lang ?? $this->getLang()->getValue()) => $this->getName()->getValue(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
