<?php

namespace App\Application\Services;

use App\Domain\Cart\Model\Cart;
use App\Domain\Cart\Services\CartService as DomainCartService;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class CartAppService
{
    public function __construct(
        private DomainCartService $cartService,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function createUserCart(int $userId): Cart
    {
        return $this->cartService->createCart($userId);
    }

    public function createGuestCart(string $guestToken): Cart
    {
        return $this->cartService->createCart(null, $guestToken);
    }

    public function getCartById(string $cartId): ?Cart
    {
        return $this->cartService->getCartById($cartId);
    }

    public function getUserCart(int $userId): ?Cart
    {
        return $this->cartService->getUserCart($userId);
    }

    public function getGuestCart(string $guestToken): ?Cart
    {
        return $this->cartService->getGuestCart($guestToken);
    }

    public function addItem(string $cartId, string $productId, int $quantity = 1): Cart
    {

        $cart = $this->cartService->getCartById($cartId);

        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        $product = $this->productRepository->findById((int)$productId);

        if (!$product) {
            throw new \DomainException("Product not found");
        }

        return $this->cartService->addItemToCart($cart, $productId, $product->getPrice(),$quantity);
    }

    public function updateItemQuantity(string $cartId, string $productId, int $quantity): Cart
    {
        $cart = $this->cartService->getCartById($cartId);
        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        return $this->cartService->updateItemQuantity($cart, $productId, $quantity);
    }

    public function removeItem(string $cartId, string $productId): Cart
    {
        $cart = $this->cartService->getCartById($cartId);
        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        return $this->cartService->removeItemFromCart($cart, $productId);
    }

    public function clearCart(string $cartId): Cart
    {
        $cart = $this->cartService->getCartById($cartId);
        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        return $this->cartService->clearCart($cart);
    }

    public function assignToUser(string $cartId, int $userId): Cart
    {
        $cart = $this->cartService->getCartById($cartId);
        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        return $this->cartService->assignCartToUser($cart, $userId);
    }

    public function mergeCarts(string $sourceCartId, string $targetCartId): Cart
    {
        $sourceCart = $this->cartService->getCartById($sourceCartId);
        $targetCart = $this->cartService->getCartById($targetCartId);

        if (!$sourceCart || !$targetCart) {
            throw new \DomainException("One or both carts not found");
        }

        return $this->cartService->mergeCarts($sourceCart, $targetCart);
    }

    public function transferGuestCartToUser(string $guestToken, int $userId): Cart
    {
        $guestCart = $this->cartService->getGuestCart($guestToken);
        $userCart = $this->cartService->getUserCart($userId);

        if (!$guestCart) {
            throw new \DomainException("Guest cart not found");
        }

        if ($userCart) {
            // Переносим все items из гостевой корзины в пользовательскую
            foreach ($guestCart->getItems() as $guestItem) {
                try {
                    // Для каждого item получаем актуальную цену продукта
                    $product = $this->productRepository->findById((int)$guestItem->getProductId());
                    if ($product) {
                        $this->cartService->addItemToCart(
                            $userCart,
                            $guestItem->getProductId(),
                            $product->getPrice(),
                            $guestItem->getQuantity()
                        );
                    }
                } catch (\DomainException $e) {
                    // Если товар уже есть в корзине, просто пропускаем
                    // или можно добавить логику обновления количества
                    continue;
                }
            }

            // Удаляем гостевую корзину после переноса
            $this->cartService->deleteCart($guestCart->getId());

            return $userCart;
        }

        // Если у пользователя нет корзины, просто привязываем гостевую
        return $this->cartService->assignCartToUser($guestCart, $userId);
    }
}
