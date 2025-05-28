<?php
namespace App\Services;

use App\Dto\Order\StoreDto;
use App\Dto\Order\UpdateDto;
use App\Models\Order;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;

class OrdersService
{
    public function __construct(
        private OrdersRepository $repository,
        private ProductsRepository $productsRepository,
    ) {}

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll();
    }

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

        $this->repository->addProducts($order, $items);
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

        $this->repository->addProducts($order, $items);
    }

    public function delete($orderId): void
    {
        $products = $this->repository->find($orderId)->getProducts();
        foreach ($products as $product) {
            $product->stock += $product->pivot->count;
            $product->save();
        }

        $this->repository->deleteProducts($orderId);
        $this->repository->delete($orderId);
    }
}