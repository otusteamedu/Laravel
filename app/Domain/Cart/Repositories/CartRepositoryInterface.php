<?php

namespace App\Domain\Cart\Repositories;

use App\Domain\Cart\Model\Cart;

interface CartRepositoryInterface
{
    public function findById(string $id): ?Cart;
    public function findByUserId(int $userId): ?Cart;
    public function findByGuestToken(string $guestToken): ?Cart;
    public function save(Cart $cart): Cart;
    public function delete(string $cartId): void;
    public function cleanupExpired(): void;
}
