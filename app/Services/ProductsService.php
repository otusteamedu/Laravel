<?php
namespace App\Services;

use App\Dto\Admin\Product\StoreDto;
use App\Dto\Admin\Product\UpdateDto;
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

    /**
     * @return Collection<array-key, Product>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function getList(string $sort, string $direction): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction);
    }

    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function getAllWithImage(): LengthAwarePaginator
    {
        return $this->repository->fetchAllWithImage();
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->repository->fetchByCategoryId($categoryId);
    }

    /**
     * @return Product
     */
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

            $product->assets()->createMany($items);
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

            $oldAssets = $product->getAssets();

            foreach ($oldAssets as $asset) {
                Storage::delete($asset->asset_url);
            }

            $product->assets()->delete();
            $product->assets()->createMany($items);
        }
    }

    public function delete($productId): void
    {
        $product = $this->repository->find($productId);
        $assets = $product->getAssets();

        foreach ($assets as $asset) {
            Storage::delete($asset->asset_url);
        }

        $product->assets()->delete();
        $this->repository->delete($productId);
    }
}