<?php

namespace App\Repositories;

use App\Dto\Admin\Product\StoreDto;
use App\Dto\Admin\Product\UpdateDto;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductsRepository
{
    const PRODUCT_FOR_PAGE = 12;

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'products.all';
        $ttl = now()->addDay();
        $tag = 'product-list';

        if ($warmup) {
            $products = Product::with('category')->get();
            Cache::tags($tag)->put($key, $products, $ttl);
            return $products;
        }

        $products = Cache::tags($tag)->remember($key, $ttl, function () {
            return Product::with('category')->get();
        });

        return $products;
    }


    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function fetchAllWithImage(int $page, bool $warmup = false): LengthAwarePaginator
    {
        $key = 'products.' . $page;
        $ttl = now()->addDay();
        $tag = 'product-list';

        if ($warmup) {
            $paginator = Product::with('first_image')->paginate(self::PRODUCT_FOR_PAGE)->withQueryString();
            Cache::tags($tag)->put($key, $paginator, $ttl);
            return $paginator;
        }

        $paginator = Cache::tags($tag)->remember($key, $ttl, function () {
            return Product::with('first_image')->paginate(self::PRODUCT_FOR_PAGE)->withQueryString();
        });

        return $paginator;
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchByIdsWithImage(array $productIds): Collection
    {
        if (empty($productIds)) {
            return new Collection();
        }

        $ids = implode('.', $productIds);
        $key = 'products.image.ids.' . $ids;
        $ttl = now()->addDay();
        $tag = 'product-list';

        $products = Cache::tags($tag)->remember($key, $ttl, function () use($productIds) {
            return Product::with('first_image')
                ->whereIn('id', $productIds)
                ->orderByRaw("FIELD(id, " . implode(',', $productIds) . ")")
                ->get();
        });

        return $products;
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchByCategoryId(int $categoryId, bool $warmup = false): Collection
    {
        $key = 'products.category.' . $categoryId;
        $ttl = now()->addDay();
        $tag = 'product-list';

        if ($warmup) {
            $products = Product::with('first_image')->where('category_id', $categoryId)->get();
            Cache::tags($tag)->put($key, $products, $ttl);
            return $products;
        }

        $products = Cache::tags($tag)->remember($key, $ttl, function () use($categoryId) {
            return Product::with('first_image')->where('category_id', $categoryId)->get();
        });

        return $products;
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchByCategoryTitle(string $categoryTitle, bool $warmup = false): Collection
    {
        $key = 'products.category_title.' . str_replace(' ', '_', $categoryTitle);
        $ttl = now()->addDay();
        $tag = 'product-list';

        if ($warmup) {
            $products = Product::whereHas('category', function($query) use ($categoryTitle) {
                $query->where('title', $categoryTitle);
            })->get();
            Cache::tags($tag)->put($key, $products, $ttl);
            return $products;
        }

        $products = Cache::tags($tag)->remember($key, $ttl, function () use ($categoryTitle) {
            return Product::whereHas('category', function($query) use ($categoryTitle) {
                $query->where('title', $categoryTitle);
            })->get();
        });

        return $products;
    }

    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function fetchList(string $sort, string $direction, int $page, bool $warmup = false): LengthAwarePaginator
    {
        $key = 'products.' . $sort . '.' . $direction . '.' . $page;
        $ttl = now()->addDay();
        $tag = 'product-list';

        $paginator = Cache::tags($tag)->get($key);

        if (!$paginator || $warmup) {
            $paginator = Product::with('category');

            if ($sort == 'category') {
                $paginator = $paginator->orderBy(Category::select('title')->whereColumn('categories.id', 'products.category_id'), $direction);
            } else {
                $paginator = $paginator->orderBy($sort, $direction);
            }

            $perPage = config('custom.perPageAdmin');
            $paginator = $paginator->paginate($perPage)->withQueryString();
            Cache::tags($tag)->put($key, $paginator, $ttl);
        }

        return $paginator;
    }

    /**
     * @return Product
     */
    public function find(int $productId, bool $warmup = false): Product
    {
        $key = 'product.' . $productId;
        $ttl = now()->addDay();

        if ($warmup) {
            $product = Product::with(['category', 'assets'])->find($productId);
            if ($product) {
                Cache::put($key, $product, $ttl);
            }
            return $product;
        }

        $product = Cache::remember($key, $ttl, function () use($productId) {
            return Product::with(['category', 'assets'])->find($productId);
        });

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @return Product
     */
    public function findShort(int $productId, bool $warmup = false): Product
    {
        $key = 'product.short.' . $productId;
        $ttl = now()->addDay();

        if ($warmup) {
            $product = Product::find($productId);
            if ($product) {
                Cache::put($key, $product, $ttl);
            }
            return $product;
        }

        $product = Cache::remember($key, $ttl, function () use($productId) {
            return Product::find($productId);
        });

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function findByIds(array $product_ids): Collection
    {
        $ids = implode('.', $product_ids);

        $key = 'products.ids.' . $ids;
        $ttl = now()->addDay();
        $tag = 'product-list';

        $products = Cache::tags($tag)->remember($key, $ttl, function () use($product_ids) {
            return Product::whereIn('id', $product_ids)->get();
        });

        return $products;
    }

    /**
     * @return int
     */
    public function count(bool $warmup = false): int
    {
        
        $key = 'products.count';
        $ttl = now()->addDay();
        
        if ($warmup) {
            $count = Product::count();
            Cache::put($key, $count, $ttl);
            return $count;
        }
        
        $count = Cache::remember($key, $ttl, function () {
            return Product::count();
        });

        return $count;
    }

    /**
     * @return Product
     */
    public function add(StoreDto $storeDto): Product
    {
        $product = new Product();
        $product->title = $storeDto->title;
        $product->description = $storeDto->description;
        $product->category_id = $storeDto->category_id;
        $product->price = $storeDto->price;
        $product->stock = $storeDto->stock;
        $product->save();

        Cache::tags('product-list')->flush();
        Cache::forget('products.count');

        return $product;
    }

    /**
     * @return Product
     */
    public function save(UpdateDto $updateDto): Product
    {
        $product = Product::find($updateDto->id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        $product->title = $updateDto->title;
        $product->description = $updateDto->description;
        $product->category_id = $updateDto->category_id;
        $product->price = $updateDto->price;
        $product->stock = $updateDto->stock;
        $product->save();

        Cache::forget('product.' . $updateDto->id);
        Cache::forget('products.category.' . $updateDto->category_id);
        Cache::tags('product-list')->flush();

        return $product;
    }

    public function delete(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }
        
        Cache::forget('product.' . $productId);
        Cache::forget('products.category.' . $product->getCategoryId());
        Cache::tags('product-list')->flush();
        Cache::forget('products.count');

        $product->delete();
    }

    public function warmupCache(): void
    {
        $products = $this->fetchAll(true);
        $productTotal = $this->count(true);

        $perPage = self::PRODUCT_FOR_PAGE;
        $totalPage = ceil($productTotal / $perPage);
        $pages = range(1, $totalPage);
        foreach ($pages as $page) {
            $this->fetchAllWithImage($page, true);
        }

        $categories = Category::all();
        $categoryIds = $categories->pluck('id')->toArray();
        $categoryTitles = $categories->pluck('title')->toArray();

        foreach ($categoryIds as $categoryId) {
            $this->fetchByCategoryId($categoryId, true);
        }
        foreach ($categoryTitles as $categoryTitle) {
            $this->fetchByCategoryTitle($categoryTitle, true);
        }

        $perPage = config('custom.perPageAdmin');
        $totalPage = ceil($productTotal / $perPage);

        $sorts = config('custom.productsSorts');
        $directions = config('custom.productsDirections');
        foreach ($sorts as $sort) {
            foreach ($directions as $direction) {
                foreach ($pages as $page) {
                    $this->fetchList($sort, $direction, $page, true);
                }
            }
        }
        
        $ids = $products->pluck('id')->toArray();
        foreach ($ids as $orderId) {
            $this->find($orderId, true);
            $this->findShort($orderId, true);
        }
    }
}