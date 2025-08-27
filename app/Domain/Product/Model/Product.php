<?php

namespace App\Domain\Product\Model;

class Product
{
    public function __construct(
          public ?int $id,
          public string $title,
          public ?string $alias,
          public ?string $text,
          public ?string $image,
          public ?array $images,
          public ?int $is_sale, // Stored as tinyInteger, accessed as bool
          public ?int $published, // Stored as tinyInteger, accessed as bool
          public ?int $order,
          public float $price,
          public ?int $user_id,
          public ?array $categories_id,
    ){
        //Assert
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryIds(): ?array
    {
        return $this->categories_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function getIsSale(): ?int
    {
        return $this->is_sale;
    }

    public function getPublished(): ?int
    {
        return $this->published;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alias' => $this->alias,
            'text' => $this->text,
            'image' => $this->image,
            'images' => $this->images,
            'is_sale' => (bool)$this->is_sale,
            'published' => (bool)$this->published,
            'order' => $this->order,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'categories' => $this->categories_id,
        ];
    }


}
