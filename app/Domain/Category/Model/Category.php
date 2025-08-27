<?php

namespace App\Domain\Category\Model;

class Category
{
    public function __construct(
        public ?int $id,
        public string $title,
        public ?string $alias,
        public ?string $text,
        public ?int $published,
        public ?int $order,

    ){
        //Assert
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPublished(): ?int
    {
        return $this->published;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alias' => $this->alias,
            'text' => $this->text,
            'published' => (bool)$this->published,
            'order' => $this->order
        ];
    }

}
