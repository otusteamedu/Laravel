<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Model\Order;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Cart\Model\Cart;
use App\Domain\Cart\Services\CartService;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private CartService $cartService
    ) {}

    public function createOrderFromCart(
        Cart $cart,
        string $email,
        ?string $name = null,
        ?string $phone = null,
        ?int $userId = null,
        ?string $shipping_address = null,
        ?string $billing_address = null,
        ?string $customer_note = null
    ): Order
    {
        $order = Order::createFromCart(
            $cart,
            $email,
            $name,
            $phone,
            $userId,
            $shipping_address,
            $billing_address,
            $customer_note,
        );
        return $this->orderRepository->save($order);
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }

    /**
     * @return Order[]
     */
    public function getUserOrders(int $userId): array
    {
        return $this->orderRepository->findByUserId($userId);
    }

    public function updateOrderStatus(int $orderId, string $status): Order
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \DomainException("Order not found");
        }

        $order->updateStatus($status);
        return $this->orderRepository->save($order);
    }

    public function cancelOrder(int $orderId): Order
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \DomainException("Order not found");
        }

        $order->cancel();
        return $this->orderRepository->save($order);
    }

    public function deleteOrder(int $orderId): void
    {
        $this->orderRepository->delete($orderId);
    }

    /**
     * @param array $criteria
     * @return Order[]
     */
    public function searchOrders(array $criteria): array
    {
        return $this->orderRepository->findByCriteria($criteria);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param array $criteria
     * @return array{data: Order[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function getOrdersPaginated(int $page = 1, int $perPage = 15, array $criteria = []): array
    {
        return $this->orderRepository->paginate($page, $perPage, $criteria);
    }
}
