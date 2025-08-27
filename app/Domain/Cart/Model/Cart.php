<?php

namespace App\Domain\Cart\Model;

use App\Domain\Product\Model\Product;

class Cart
{
    private ?string $id;
    private ?int $userId;
    private ?string $guestToken;
    private array $items;
    private \DateTimeInterface $expiresAt;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    public function __construct(
        ?string $id,
        ?int $userId,
        ?string $guestToken,
        array $items = [],
        \DateTimeInterface $expiresAt = null,
        \DateTimeInterface $createdAt = null,
        \DateTimeInterface $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->guestToken = $guestToken;
        $this->items = $items;
        $this->expiresAt = $expiresAt ?? new \DateTime('+30 days');
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt ?? new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getGuestToken(): ?string
    {
        return $this->guestToken;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function isGuestCart(): bool
    {
        return $this->guestToken !== null;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new \DateTime();
    }

    public function addItem(string $productId, float $price, int $quantity = 1): void
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                $item->increaseQuantity($quantity);
                $this->updatedAt = new \DateTime();
                return;
            }
        }


        $this->items[] = new CartItem(null, $productId, $price, $quantity);

        $this->updatedAt = new \DateTime();
    }

    public function updateItemQuantity(string $productId, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                if ($quantity <= 0) {
                    $this->removeItem($productId);
                    return;
                }
                $item->setQuantity($quantity);
                $this->updatedAt = new \DateTime();
                return;
            }
        }

        throw new \DomainException("Product not found in cart");
    }

    public function removeItem(string $productId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getProductId() === $productId) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                $this->updatedAt = new \DateTime();
                return;
            }
        }

        throw new \DomainException("Product not found in cart");
    }

    public function clear(): void
    {
        $this->items = [];
        $this->updatedAt = new \DateTime();
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function getTotalQuantity(): int
    {
        $quantity = 0;
        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }
        return $quantity;
    }

    public function assignToUser(int $userId): void
    {
        $this->userId = $userId;
        $this->guestToken = null;
        $this->expiresAt = new \DateTime('+365 days'); // User carts don't expire
        $this->updatedAt = new \DateTime();
    }

    public function refreshExpiry(): void
    {
        if ($this->isGuestCart()) {
            $this->expiresAt = new \DateTime('+30 days');
            $this->updatedAt = new \DateTime();
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'guest_token' => $this->guestToken,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
            'total' => $this->getTotal(),
            'total_quantity' => $this->getTotalQuantity(),
            'expires_at' => $this->expiresAt->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
