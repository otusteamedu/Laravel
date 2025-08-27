<?php

namespace App\Application\DTO;

class ProductData
{
    public function __construct(
        public string $title,
        public ?string $alias = null,
        public ?string $description = null,
        public ?string $image = null,
        public array $images = [],
        public bool $isSale = false,
        public bool $published = true,
        public int $order = 0,
        public float $price = 0.0,
        public ?array $categoryIds = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            alias: $data['alias'] ?? null,
            description: $data['description'] ?? $data['text'] ?? null,
            image: $data['image'] ?? null,
            images: $data['images'] ?? [],
            isSale: $data['is_sale'] ?? false,
            published: $data['published'] ?? true,
            order: $data['order'] ?? 0,
            price: (float)($data['price'] ?? 0),
            categoryIds: $data['category_ids'] ?? $data['categories'] ?? null
        );
    }

    public static function fromRequest(array $requestData): self
    {
        return self::fromArray($requestData);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'alias' => $this->alias,
            'description' => $this->description,
            'image' => $this->image,
            'images' => $this->images,
            'is_sale' => $this->isSale,
            'published' => $this->published,
            'order' => $this->order,
            'price' => $this->price,
            'category_ids' => $this->categoryIds,
        ];
    }

    public function validate(): void
    {
        if (empty($this->title)) {
            throw new \InvalidArgumentException('Product title is required');
        }

        if ($this->price < 0) {
            throw new \InvalidArgumentException('Product price cannot be negative');
        }

        if ($this->order < 0) {
            throw new \InvalidArgumentException('Product order cannot be negative');
        }
    }
}
