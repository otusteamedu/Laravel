<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Category\CategoryName;
use App\Domain\ValueObjects\Category\CategoryDescription;

class Category extends BaseModel implements BusinessModelsInterface
{
    private CategoryName $name;
    private CategoryDescription $description;
    private string $apiId;
    private ?string $created_at;

    public function __construct(
        CategoryName $name,
        CategoryDescription $description,
        Lang $lang,
        string $apiId,
        ?int $id = null,
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->name = $name;
        $this->lang = $lang;
        $this->apiId = $apiId;
        $this->created_at = $created_at;
    }

    public function getName(): CategoryName
    {
        return $this->name;
    }

    public function getDescription(): CategoryDescription
    {
        return $this->description;
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function rename(CategoryName $newName): void
    {
        if ($this->name === $newName) {
            throw new NotValidItemDomainException("Новое название совпадает со старым");
        }
        $this->name = $newName;
    }

    public function updateDescription(CategoryDescription $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function toArray(): array
    {
        $array = [
            'name' => $this->getName()->getValue(),
            'description' => $this->getDescription()->getValue(),
            'created_at' => $this->getCreatedAt(),
        ];
        return $array;
    }
}
