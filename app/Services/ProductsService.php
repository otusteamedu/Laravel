<?php
namespace App\Services;

use App\Dto\Product\StoreDto;
use App\Dto\Product\UpdateDto;
use App\Repositories\ProductsRepository;
use Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

class ProductsService
{
    public function __construct(
        private ProductsRepository $repository,
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    public function getList(string $sort, string $direction): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction);
    }

    public function getAllWithImage(): LengthAwarePaginator
    {
        return $this->repository->fetchAllWithImage();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->repository->fetchByCategoryId($categoryId);
    }

    public function getById($productId): Product
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