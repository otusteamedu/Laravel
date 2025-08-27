<?php

namespace App\Domain\Order\Model;

class OrderItem
{
    private ?int $id;
    private ?int $orderId; // Делаем nullable
    private string $productId;
    private int $quantity;
    private float $price;
    private float $total;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    public function __construct(
        ?int $id,
        string $productId,
        int $quantity,
        float $price,
        float $total,
        ?int $orderId = null,
        ?\DateTimeInterface $createdAt = null,
        ?\DateTimeInterface $updatedAt = null
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->total = $total;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt ?? new \DateTime();

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->quantity <= 0) {
            throw new \DomainException("Quantity must be positive");
        }

        if ($this->price <= 0) {
            throw new \DomainException("Price must be positive");
        }

        if ($this->total <= 0) {
            throw new \DomainException("Total must be positive");
        }

        // Verify that total matches quantity * price
        $calculatedTotal = $this->quantity * $this->price;
        if (abs($this->total - $calculatedTotal) > 0.01) {
            throw new \DomainException("Total amount mismatch: expected {$calculatedTotal}, got {$this->total}");
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
        $this->updatedAt = new \DateTime();
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->orderId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
