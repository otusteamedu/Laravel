<?php
namespace App\Services;

use App\Dto\Admin\Product\StoreDto;
use App\Dto\Admin\Product\UpdateDto;
use App\Dto\Search\SearchDto;
use App\Repositories\ProductsRepository;
use Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;
use Meilisearch\Client;

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
    public function getList(string $sort, string $direction, int $page): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction, $page);
    }

    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function getAllWithImage(int $page): LengthAwarePaginator
    {
        return $this->repository->fetchAllWithImage($page);
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function getByIdsWithImage(array $productIds): Collection
    {
        return $this->repository->fetchByIdsWithImage($productIds);
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->repository->fetchByCategoryId($categoryId);
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function getByCategoryTitle(string $categoryTitle): Collection
    {
        return $this->repository->fetchByCategoryTitle($categoryTitle);
    }

    /**
     * @return Product
     */
    public function getById($productId): Product
    {
        return $this->repository->find($productId);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->repository->count();
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

    /**
     * @return Collection<array-key, Product>
     */
    public function search(SearchDto $dto): Collection
    {
        $client = new Client(env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
        $index = $client->index('products');

        $filters = [];

        if ($dto->category_id) {
            $filters[] = "category_id = {$dto->category_id}";
        }
        
        if ($dto->min_price) {
            $filters[] = "price >= {$dto->min_price}";
        }
        if ($dto->max_price) {
            $filters[] = "price <= {$dto->max_price}";
        }
        
        if ($dto->brands) {
            $values = implode(',', $dto->brands);
            $filters[] = "brand_id IN [{$values}]";
        }

        if ($dto->rating) {
            $filters[] = "rating >= {$dto->rating}";
        }

        if ($dto->min_screen) {
            $filters[] = "screen_size >= {$dto->min_screen}";
        }
        if ($dto->max_screen) {
            $filters[] = "screen_size <= {$dto->max_screen}";
        }

        if ($dto->min_ram) {
            $filters[] = "ram >= {$dto->min_ram}";
        }
        if ($dto->max_ram) {
            $filters[] = "ram <= {$dto->max_ram}";
        }

        if ($dto->min_builtin) {
            $filters[] = "builtin_memory >= {$dto->min_builtin}";
        }
        if ($dto->max_builtin) {
            $filters[] = "builtin_memory <= {$dto->max_builtin}";
        }

        $params = [
            'filter' => implode(' AND ', $filters),
            'sort' => ['price:asc'],
        ]; 
        $results = $index->search($dto->q, $params);

        $productIds = collect($results->getHits())->pluck('id')->toArray();
        $products = $this->getByIdsWithImage($productIds);

        return $products;
    }
}