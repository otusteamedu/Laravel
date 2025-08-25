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
    private ?string $created_at;

    public function __construct(
        CategoryName $name,
        CategoryDescription $description,
        Lang $lang,
        ?int $id = null,
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lang = $lang;
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
                'description_' . ($lang ?? $this->getLang()->getValue()) => $this->getDescription()->getValue(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
