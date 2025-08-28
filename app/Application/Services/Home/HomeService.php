<?php

namespace App\Application\Services\Home;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Application\Services\Measure\MeasureRepositoryInterface;
use App\Application\Services\Product\ProductRepositoryInterface;
use App\Application\Services\Recipe\RecipeRepositoryInterface;
use App\Domain\ValueObjects\Lang;

final readonly class HomeService implements HomeServiceInterface
{
    public ProductRepositoryInterface $productRrepository;
    public MeasureRepositoryInterface $measureRrepository;
    public RecipeRepositoryInterface $recipeRrepository;

    public function __construct(
        ProductRepositoryInterface $productRrepository,
        MeasureRepositoryInterface $measureRrepository,
        RecipeRepositoryInterface $recipeRrepository,
    ) {
        $this->productRrepository = $productRrepository;
        $this->measureRrepository = $measureRrepository;
        $this->recipeRrepository = $recipeRrepository;
    }

    public function prepairDataForIndex(): array
    {
        try {
            $productModels = $this->productRrepository->getAll();
        } catch (\Throwable $th) {
            throw new ServiceException(previos: $th);
        }
        if (empty($productModels)) {
            throw new NotFoundServiceException('Записи отсутствуют.');
        };
        foreach ($productModels as $key => $model) {
            $productModels[$key] = $model->toArray();
        }
        return ['products' => $productModels];
    }

    public function getRecipe(array $products, int $portions): array
    {
        try {
            $productIds = array_column($products, 'product_id');
            $measureIds = array_column($products, 'measure_id');
            $recipes = collect(
                $this->recipeRrepository->getRecipeByProductIdAndMeasureId($productIds, $measureIds)
            );
            $recipes = $recipes->map(function ($mpr) use ($products, $portions) {
                $productId = $mpr->getProduct()->getId();
                $measureId = $mpr->getMeasure()->getId();
                $userProduct = collect($products)->first(
                    fn($p) => (int)$p['product_id'] === $productId && (int)$p['measure_id'] === $measureId
                );
                $perPortion = (float)str_replace(',', '.', (string)$mpr->getMeasureProductRecipeValue()->getValue());
                $required   = $perPortion * (int)$portions;
                $mpr->enough = $userProduct ? ((float)$userProduct['product_value'] >= $required) : false;
                return $mpr;
            });
            $recipesGrouped = $recipes->groupBy(fn($mpr) => $mpr->getRecipe()->getId());
            $validRecipeIds = $recipesGrouped
                ->filter(function ($items) {
                    $total  = $items->count();
                    $enough = $items->filter(fn($i) => !empty($i->enough))->count();
                    return $total > 0 && ($enough / $total) >= 0.5;
                })
                ->keys()
                ->all();
            $result = [];
            foreach($validRecipeIds as $id) {
                $result[] = $this->recipeRrepository->findById($id)->toArray();
            }
            return $result;
        } catch (\Throwable $th) {
            throw new ServiceException(previos: $th);
        }
    }

    public function getMeasureByProduct(int $productId, string $lang): array
    {
        try {
            $lang = new Lang($lang);
            $measure = $this->measureRrepository->getMeaureByProductId($productId, $lang);
            return $measure;
        } catch (\Throwable $th) {
            throw new ServiceException(previos: $th);
        }
    }
}
