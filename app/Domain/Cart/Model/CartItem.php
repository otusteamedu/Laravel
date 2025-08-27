<?php

namespace App\Domain\Cart\Model;

class CartItem
{
    private ?string $id;

    private string $productId;
    private float $price;
    private int $quantity;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    public function __construct(
        ?string $id,
        string $productId,
        float $price,
        int $quantity = 1,
        \DateTimeInterface $createdAt = null,
        \DateTimeInterface $updatedAt = null
    ) {

        $this->id = $id;
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt ?? new \DateTime();

    }

    private function validate(): void
    {
        if ($this->quantity <= 0) {
            throw new \DomainException("Quantity must be positive");
        }

        if ($this->price <= 0) {
            throw new \DomainException("Price must be positive");
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \DomainException("Quantity must be positive");
        }
        $this->quantity = $quantity;
        $this->updatedAt = new \DateTime();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function increaseQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
        $this->updatedAt = new \DateTime();
    }

    public function decreaseQuantity(int $amount = 1): void
    {
        if ($this->quantity - $amount <= 0) {
            throw new \DomainException("Quantity cannot be zero or negative");
        }
        $this->quantity -= $amount;
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getTotal():int|float
    {
        return $this->quantity * $this->price;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
