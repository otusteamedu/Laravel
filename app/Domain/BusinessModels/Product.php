<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Product\ProductName;

class Product extends BaseModel implements BusinessModelsInterface
{
    private ProductName $name;
    private ?string $created_at;

    public function __construct(
        ProductName $name, 
        Lang $lang,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
        $this->created_at = $created_at;
    }

    public function getName(): ProductName 
    {
        return $this->name;
    }

    public function rename(ProductName $newName): void 
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
            'id' => $this->getId(),
            'name' => $this->getName()->getValue(),
            'created_at' => $this->getCreatedAt(),
        ];
        return $array;
    }
}
