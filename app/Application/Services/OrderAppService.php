<?php

namespace App\Application\Services;

use App\Domain\Order\Model\Order;
use App\Domain\Order\Services\OrderService as DomainOrderService;
use App\Domain\Cart\Services\CartService;

class OrderAppService
{
    public function __construct(
        private DomainOrderService $orderService,
        private CartService $cartService
    ) {}

    public function createOrderFromCart(
        string $cartId,
        string $email,
        ?string $name = null,
        ?string $phone = null,
        ?int $userId = null,
        ?string $shipping_address = null,
        ?string $billing_address = null,
        ?string $customer_note = null
    ): Order
    {
        $cart = $this->cartService->getCartById($cartId);

        if (!$cart) {
            throw new \DomainException("Cart not found");
        }

        if (count($cart->getItems()) === 0) {
            throw new \DomainException("Cannot create order from empty cart");
        }

        return $this->orderService->createOrderFromCart(
            $cart,
            $email,
            $name,
            $phone,
            $userId,
            $shipping_address,
            $billing_address,
            $customer_note
        );
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderService->getOrderById($id);
    }

    /**
     * @return Order[]
     */
    public function getUserOrders(int $userId): array
    {
        return $this->orderService->getUserOrders($userId);
    }

    public function updateOrderStatus(int $orderId, string $status): Order
    {
        return $this->orderService->updateOrderStatus($orderId, $status);
    }

    public function cancelOrder(int $orderId): Order
    {
        return $this->orderService->cancelOrder($orderId);
    }

    public function deleteOrder(int $orderId): void
    {
        $this->orderService->deleteOrder($orderId);
    }

    /**
     * @param array $criteria
     * @return Order[]
     */
    public function searchOrders(array $criteria): array
    {
        return $this->orderService->searchOrders($criteria);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param array $criteria
     * @return array{data: Order[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function getOrdersPaginated(int $page = 1, int $perPage = 15, array $criteria = []): array
    {
        return $this->orderService->getOrdersPaginated($page, $perPage, $criteria);
    }
}
