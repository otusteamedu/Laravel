<?php
namespace App\Services;

use App\Dto\Admin\Order\StoreDto;
use App\Dto\Admin\Order\UpdateDto;
use App\Models\Order;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrdersService
{
    public function __construct(
        private OrdersRepository $repository,
        private ProductsRepository $productsRepository,
    ) {}

    /**
     * @return Collection<array-key, Order>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    /**
     * @return LengthAwarePaginator<array-key, Order>
     */
    public function getList(string $sort, string $direction): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction);
    }

    /**
     * @return Order
     */
    public function getById($orderId): Order
    {
        return $this->repository->find($orderId);
    }

    public function add(StoreDto $storeDto, array $product_ids, array $counts): void
    {
        $order = $this->repository->add($storeDto);
        $products = $this->productsRepository->findByIds($product_ids);
        $countsByProductId = array_combine($product_ids, $counts);

        $items = [];
        foreach ($products as $product) {
            $id = $product->getId();
            $items[$id] = ['count' => $countsByProductId[$id], 'paid_price' => $product->getPrice()];
        }

        $order->products()->sync($items);
    }

    public function update(UpdateDto $updateDto, array $product_ids, array $counts): void
    {
        $order = $this->repository->save($updateDto);
        $products = $this->productsRepository->findByIds($product_ids);
        $countsByProductId = array_combine($product_ids, $counts);

        $items = [];
        foreach ($products as $product) {
            $id = $product->getId();
            $count = $countsByProductId[$id];
            if ($count > 0) {
                $items[$id] = ['count' => $count, 'paid_price' => $product->getPrice()];
            }
        }

        $order->products()->sync($items);
    }

    public function delete($orderId): void
    {
        $order = $this->repository->find($orderId);
        $products = $order->getProducts();
        foreach ($products as $product) {
            $product->stock += $product->pivot->count;
            $product->save();
        }

        $order->products()->detach();
        $this->repository->delete($orderId);
    }
}