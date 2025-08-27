<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Domain\Order\Model\Order;
use App\Domain\Order\Model\OrderItem;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Eloquent\Models\Order as EloquentOrder;
use App\Infrastructure\Eloquent\Models\OrderItem as EloquentOrderItem;

class OrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        $model = EloquentOrder::with('items')->find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByUserId(int $userId): array
    {
        return EloquentOrder::with('items')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByStatus(string $status): array
    {
        return EloquentOrder::with('items')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Order $order): Order
    {
        $data = [
            'user_id' => $order->getUserId(),
            'status' => $order->getStatus(),
            'total_amount' => $order->getTotalAmount(),
            'email' => $order->getEmail(),
            'name' => $order->getName(),
            'phone' => $order->getPhone(),
            'shipping_address' => $order->getShippingAddress(),
            'billing_address' => $order->getBillingAddress(),
            'customer_note' => $order->getCustomerNote(),
        ];

        if ($order->getId()) {
            EloquentOrder::where('id', $order->getId())->update($data);
            $model = EloquentOrder::find($order->getId());
        } else {
            $model = EloquentOrder::create($data);
        }

        // Save order items
        $this->saveOrderItems($order, $model);

        $model->load('items');

        return $this->toEntity($model);
    }

    private function saveOrderItems(Order $order, EloquentOrder $model): void
    {
        $currentItemIds = [];

        foreach ($order->getItems() as $item) {
            $itemData = [
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'total' => $item->getTotal(),
            ];

            if ($item->getId()) {
                EloquentOrderItem::where('id', $item->getId())->update($itemData);
                $currentItemIds[] = $item->getId();
            } else {
                $newItem = $model->items()->create($itemData);
                $currentItemIds[] = $newItem->id;
            }
        }

        // Remove items that are no longer in the order
        $model->items()->whereNotIn('id', $currentItemIds)->delete();
    }

    public function delete(int $orderId): void
    {
        EloquentOrder::destroy($orderId);
    }

    public function findByCriteria(array $criteria): array
    {
        $query = EloquentOrder::with('items');

        if (isset($criteria['user_id'])) {
            $query->where('user_id', $criteria['user_id']);
        }

        if (isset($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        if (isset($criteria['email'])) {
            $query->where('email', 'like', '%' . $criteria['email'] . '%');
        }

        if (isset($criteria['min_amount'])) {
            $query->where('total_amount', '>=', $criteria['min_amount']);
        }

        if (isset($criteria['max_amount'])) {
            $query->where('total_amount', '<=', $criteria['max_amount']);
        }

        if (isset($criteria['start_date'])) {
            $query->where('created_at', '>=', $criteria['start_date']);
        }

        if (isset($criteria['end_date'])) {
            $query->where('created_at', '<=', $criteria['end_date']);
        }

        $sort = $criteria['sort'] ?? 'created_at';
        $direction = $criteria['direction'] ?? 'desc';

        $query->orderBy($sort, $direction);

        return $query->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function paginate(int $page = 1, int $perPage = 15, array $criteria = []): array
    {
        $query = EloquentOrder::with('items');

        if (isset($criteria['user_id'])) {
            $query->where('user_id', $criteria['user_id']);
        }

        if (isset($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        if (isset($criteria['email'])) {
            $query->where('email', 'like', '%' . $criteria['email'] . '%');
        }

        $sort = $criteria['sort'] ?? 'created_at';
        $direction = $criteria['direction'] ?? 'desc';

        $paginator = $query->orderBy($sort, $direction)
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $paginator->getCollection()
                ->map(fn($model) => $this->toEntity($model))
                ->toArray(),
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
        ];
    }

    private function toEntity(EloquentOrder $model): Order
    {
        $items = $model->items->map(function ($itemModel) use ($model) {
            return new OrderItem(
                $itemModel->id,
                $itemModel->product_id,
                $itemModel->quantity,
                (float)$itemModel->price,
                (float)$itemModel->total,
                $model->id,
                $itemModel->created_at,
                $itemModel->updated_at
            );
        })->toArray();

        return new Order(
            $model->id,
            $model->user_id,
            $model->status,
            (float)$model->total_amount,
            $model->email,
            $model->name,
            $model->phone,
            $model->shipping_address,
            $model->billing_address,
            $model->customer_note,
            $items,
            $model->created_at,
            $model->updated_at
        );
    }
}
