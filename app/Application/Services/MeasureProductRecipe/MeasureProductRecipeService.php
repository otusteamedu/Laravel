<?php

namespace App\Application\Services\MeasureProductRecipe;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Application\Services\Category\CategoryRepositoryInterface;
use App\Application\Services\Measure\MeasureRepositoryInterface;
use App\Application\Services\Product\ProductRepositoryInterface;
use App\Domain\Factories\MeasureProductRecipe\MeasureProductRecipeFactory;
use App\Domain\ValueObjects\Lang;
use App\Infrastructure\Repositories\Recipe\RecipeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

final readonly class MeasureProductRecipeService implements MeasureProductRecipeServiceInterface
{
    private MeasureProductRecipeRepositoryInterface $measureProductRecipeRepository;
    private RecipeRepository $recipeRepository;
    private ProductRepositoryInterface $productRepository;
    private MeasureRepositoryInterface $measureRepository;

    public function __construct(
        MeasureProductRecipeRepositoryInterface $measureProductRecipeRepository,
        RecipeRepository $recipeRepository,
        ProductRepositoryInterface $productRepository,
        MeasureRepositoryInterface $measureRepository
    ) {
        $this->measureProductRecipeRepository = $measureProductRecipeRepository;
        $this->recipeRepository = $recipeRepository;
        $this->productRepository = $productRepository;
        $this->measureRepository = $measureRepository;
    }

    // public function prepairDataForIndex(): array 
    // {
    //     try {
    //         $models = $this->measureProductRecipeRepository->getAll();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     if (empty($models)) {
    //         throw new NotFoundServiceException('Записи отсутствуют.');
    //     };
    //     try {
    //         $models = collect($models)->map(function($model) {
    //             return (new MeasureProductRecipeDTO($model));
    //         })->toArray();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     return $models;
    // }

    public function store(
        string $recipeApiId,
        string $productName,
        string $measureName,
        ?string $value,
        ?string $lang = null,
    ): void {
        try {
            $lang = new Lang($lang);
            $recipe = $this->recipeRepository->findByApiId($recipeApiId);
            $product = $this->productRepository->findByName($productName, $lang);
            $measure = $this->measureRepository->findByName($measureName, $lang);
            $model = MeasureProductRecipeFactory::make(
                $recipe,
                $product,
                $measure,
                $value
            );
            $this->measureProductRecipeRepository->store($model);
        } catch (\Throwable $th) {
            throw new ServiceException(
                message: 'Запись продукта с мерой не добавлена',
                previos: $th
            );
        }
    }

    // public function prepairDataForEdit(int $id): MeasureProductRecipeDTO 
    // {
    //     try {
    //         $model = $this->measureProductRecipeRepository->findById($id);
    //         return new MeasureProductRecipeDTO($model);
    //     } catch (ModelNotFoundException $e) {
    //         throw new NotFoundServiceException(
    //             message:'Запись не найдена для редактирования'
    //         );
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    // }

    // public function update(int $id, string $name): void 
    // {
    //     try {
    //         $model = $this->measureProductRecipeRepository->findById($id);
    //         $newName = new MeasureProductRecipeName($name);
    //         $model->rename($newName);
    //         $this->measureProductRecipeRepository->update($model);
    //     } catch (ModelNotFoundException $e) {
    //         throw new NotFoundServiceException(
    //             message:'Запись не найдена для редактирования'
    //         );
    //     } catch (QueryException $e) {
    //         throw new ServiceException(
    //             message:'Запись не сохранена',
    //             previos:$e
    //         );
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    // }

    // public function delete(int $id): void 
    // {
    //     try {
    //         $model = $this->measureProductRecipeRepository->delete($id);
    //     } catch (ModelNotFoundException $e) {
    //         throw new NotFoundServiceException(
    //             message:'Запись не найдена для удаления'
    //         );
    //     } catch (QueryException $e) {
    //         throw new ServiceException(
    //             message:'Запись не удалена',
    //             previos:$e
    //         );
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    // }

    public function existingMeasureProductRecipeFromApi(): array
    {
        try {
            return $this->measureProductRecipeRepository->getValueByField('api_id');
        } catch (\Throwable $th) {
            throw new ServiceException(
                message: 'Существующие категории по API не найдены',
                previos: $th
            );
        }
    }
}
