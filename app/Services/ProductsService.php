<?php
namespace App\Services;

use App\Dto\Product\StoreDto;
use App\Dto\Product\UpdateDto;
use App\Repositories\ProductsRepository;
use Storage;

class ProductsService
{
    public function __construct(
        private ProductsRepository $repository,
    ) {}

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll();
    }

    public function getList(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->fetchList();
    }

    public function getById($productId): \App\Models\Product
    {
        return $this->repository->find($productId);
    }

    public function add(StoreDto $storeDto, ?array $assets = null): void
    {
        $product = $this->repository->add($storeDto);

        if (!empty($assets)) {
            $items = [];
            foreach ($assets as $file) {
                $path = $file->store('uploads');
                $type = $file->extension() == 'mp4' ? 'video' : 'image';
                $items[] = ['asset_url' => $path, 'type' => $type];
            }

            $this->repository->addAssets($product, $items);
        }

    }

    public function update(UpdateDto $updateDto, ?array $assets = null): void
    {
        $product = $this->repository->save($updateDto);

        if (!empty($assets)) {
            $items = [];
            foreach ($assets as $file) {
                $path = $file->store('uploads');
                $type = $file->extension() == 'mp4' ? 'video' : 'image';
                $items[] = ['asset_url' => $path, 'type' => $type];
            }

            $this->repository->deleteAssets($updateDto->id);
            $this->repository->addAssets($product, $items);
        }
    }

    public function delete($productId): void
    {
        $assets = $this->repository->fetchAssets($productId);
        foreach ($assets as $asset) {
            Storage::delete($asset->asset_url);
        }

        $this->repository->deleteAssets($productId);
        $this->repository->delete($productId);
    }
}