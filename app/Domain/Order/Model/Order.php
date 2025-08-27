<?php

namespace App\Domain\Order\Model;

use App\Domain\User\Model\User;
use App\Domain\Cart\Model\Cart;

class Order
{
    private ?int $id;
    private ?int $userId;
    private string $status;
    private float $totalAmount;
    private string $email;
    private ?string $name;
    private ?string $phone;
    private ?string $shippingAddress;
    private ?string $billingAddress;
    private ?string $customerNote;
    private array $items;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public function __construct(
        ?int $id,
        ?int $userId,
        string $status,
        float $totalAmount,
        string $email,
        ?string $name = null,
        ?string $phone = null,
        ?string $shippingAddress = null,
        ?string $billingAddress = null,
        ?string $customerNote = null,
        array $items = [],
        ?\DateTimeInterface $createdAt = null,
        ?\DateTimeInterface $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->status = $status;
        $this->totalAmount = $totalAmount;
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->shippingAddress = $shippingAddress;
        $this->billingAddress = $billingAddress;
        $this->customerNote = $customerNote;
        $this->items = $items;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt ?? new \DateTime();

        $this->validate();
    }

    private function validate(): void
    {
        if (!in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED
        ])) {
            throw new \DomainException("Invalid order status: {$this->status}");
        }

        if ($this->totalAmount <= 0) {
            throw new \DomainException("Total amount must be positive");
        }

        if (empty($this->email)) {
            throw new \DomainException("Email is required");
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException("Invalid email format");
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function getCustomerNote(): ?string
    {
        return $this->customerNote;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->updatedAt = new \DateTime();
        $this->validate();
    }

    public function cancel(): void
    {
        if (!$this->canBeCancelled()) {
            throw new \DomainException("Order cannot be cancelled in current status: {$this->status}");
        }

        $this->status = self::STATUS_CANCELLED;
        $this->updatedAt = new \DateTime();
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
        $this->updatedAt = new \DateTime();
    }

    public function calculateTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'status' => $this->status,
            'total_amount' => $this->totalAmount,
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
            'shipping_address' => $this->shippingAddress,
            'billing_address' => $this->billingAddress,
            'customer_note' => $this->customerNote,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function createFromCart(
        Cart $cart,
        string $email,
        ?string $name = null,
        ?string $phone = null,
        ?int $userId = null,
        ?string $shipping_address = null,
        ?string $billing_address = null,
        ?string $customer_note = null,
    ): self
    {

        $items = array_map(function ($cartItem) {
            return new OrderItem(
                null,
                $cartItem->getProductId(),
                $cartItem->getQuantity(),
                $cartItem->getTotal() / $cartItem->getQuantity(), // price per unit
                $cartItem->getTotal()
            );
        }, $cart->getItems());

        return new self(
            null,
            $userId,
            self::STATUS_PENDING,
            $cart->getTotal(),
            $email,
            $name,
            $phone,
            $shipping_address,
            $billing_address,
            $customer_note,
            $items
        );
    }
}
