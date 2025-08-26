<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Product\ProductDescription;
use App\Domain\ValueObjects\Product\ProductName;

class Product extends BaseModel implements BusinessModelsInterface
{
    private ProductName $name;
    private ProductDescription $descripton;
    private ?string $created_at;

    public function __construct(
        ProductName $name, 
        ProductDescription $descripton,
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

    public function getDescription(): ProductDescription 
    {
        return $this->descripton;
    }

    public function rename(ProductName $newName): void 
    {
        if ($this->name === $newName) {
            throw new NotValidItemDomainException("Новое название совпадает со старым");
        }
        $this->name = $newName;
    }

    public function updateDescription(ProductDescription $newDescription): void 
    {
        $this->descripton = $newDescription;
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
                'description_' . $this->getLang()->getValue() => $this->getDescription()->getValue(),
            ];
        } else {
            $array = [
                'id' => $this->getId(),
                'name_' . ($lang ?? $this->getLang()->getValue()) => $this->getName()->getValue(),
                'description_' . $this->getLang()->getValue() => $this->getDescription()->getValue(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
