<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Tag\AreaName;
use App\Domain\ValueObjects\Tag\TagName;

class Tag extends BaseModel implements BusinessModelsInterface
{
    private TagName $name;
    private ?string $created_at;

    public function __construct(
        TagName $name, 
        Lang $lang,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
        $this->created_at = $created_at;
    }

    public function getName(): TagName 
    {
        return $this->name;
    }

    public function rename(TagName $newName): void 
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

        ];
        return $array;
    }
}
