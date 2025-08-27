<?php

namespace App\Domain\Cart\Services;

use App\Domain\Cart\Model\Cart;
use App\Domain\Cart\Repositories\CartRepositoryInterface;


class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function createCart(?int $userId = null, ?string $guestToken = null): Cart
    {
        if ($userId !== null) {
            $existingCart = $this->cartRepository->findByUserId($userId);
            if ($existingCart) {
                return $existingCart;
            }
        }

        if ($guestToken !== null) {
            $existingCart = $this->cartRepository->findByGuestToken($guestToken);
            if ($existingCart) {
                return $existingCart;
            }
        }

        $cart = new Cart(null, $userId, $guestToken);
        return $this->cartRepository->save($cart);
    }

    public function getCartById(string $cartId): ?Cart
    {
        return $this->cartRepository->findById($cartId);
    }

    public function getUserCart(int $userId): ?Cart
    {
        return $this->cartRepository->findByUserId($userId);
    }

    public function getGuestCart(string $guestToken): ?Cart
    {
        return $this->cartRepository->findByGuestToken($guestToken);
    }

    public function addItemToCart(Cart $cart, string $productId, float $price, int $quantity = 1): Cart
    {
        $cart->addItem($productId, $price, $quantity);
        return $this->cartRepository->save($cart);
    }

    public function updateItemQuantity(Cart $cart, string $productId, int $quantity): Cart
    {
        $cart->updateItemQuantity($productId, $quantity);
        return $this->cartRepository->save($cart);
    }

    public function removeItemFromCart(Cart $cart, string $productId): Cart
    {
        $cart->removeItem($productId);
        return $this->cartRepository->save($cart);
    }

    public function clearCart(Cart $cart): Cart
    {
        $cart->clear();
        return $this->cartRepository->save($cart);
    }

    public function assignCartToUser(Cart $cart, int $userId): Cart
    {
        $cart->assignToUser($userId);
        return $this->cartRepository->save($cart);
    }

    public function mergeCarts(Cart $sourceCart, Cart $targetCart): Cart
    {
        foreach ($sourceCart->getItems() as $sourceItem) {
            $productId = $sourceItem->getProduct()->getId();
            $quantity = $sourceItem->getQuantity();

            try {
                $targetCart->addItem($sourceItem->getProduct(), $quantity);
            } catch (\DomainException $e) {
                // Item already exists, update quantity
                $targetCart->updateItemQuantity($productId, $quantity);
            }
        }

        $this->cartRepository->delete($sourceCart->getId());
        return $this->cartRepository->save($targetCart);
    }

    public function refreshCartExpiry(Cart $cart): Cart
    {
        $cart->refreshExpiry();
        return $this->cartRepository->save($cart);
    }

    public function deleteCart(string $cartId): void
    {
        $this->cartRepository->delete($cartId);
    }

    public function cleanupExpiredCarts(): void
    {
        $this->cartRepository->cleanupExpired();
    }
}
