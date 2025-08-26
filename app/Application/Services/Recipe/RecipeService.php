<?php

namespace App\Application\Services\Recipe;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Application\Services\Category\CategoryRepositoryInterface;
use App\Domain\Factories\Area\AreaFactory;
use App\Domain\Factories\Recipe\RecipeFactory;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Recipe\RecipeName;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;

final readonly class RecipeService implements RecipeServiceInterface
{
    private RecipeRepositoryInterface $recipeRepository;
    private AreaRepositoryInterface $areaRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        AreaRepositoryInterface $areaRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->areaRepository = $areaRepository;
        $this->categoryRepository = $categoryRepository;
    }

    // public function prepairDataForIndex(): array 
    // {
    //     try {
    //         $models = $this->recipeRepository->getAll();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     if (empty($models)) {
    //         throw new NotFoundServiceException('Записи отсутствуют.');
    //     };
    //     try {
    //         $models = collect($models)->map(function($model) {
    //             return (new RecipeDTO($model));
    //         })->toArray();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     return $models;
    // }

    public function store(
        string $name,
        string $instruction,
        string $lang,
        ?string $apiId = null,
        ?string $alternate = null,
        ?string $categoryName = null,
        ?string $areaName = null
    ): void {
        try {
            $lang = new Lang($lang);
            if (!is_null($categoryName)) {
                $category = $this->categoryRepository->findByName($categoryName, $lang);
            }
            if (!is_null($areaName)) {
                $area = $this->areaRepository->findByName($areaName, $lang);
            }
            $model = RecipeFactory::make(
                $name,
                $instruction,
                $lang->getValue(),
                $apiId,
                $alternate,
                $category,
                $area,
            );
            $this->recipeRepository->store($model);
        } catch (\Throwable $th) {
            throw new ServiceException(
                message: 'Запись рецепта не добавлена',
                previos: $th
            );
        }
    }

    // public function prepairDataForEdit(int $id): RecipeDTO 
    // {
    //     try {
    //         $model = $this->recipeRepository->findById($id);
    //         return new RecipeDTO($model);
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
    //         $model = $this->recipeRepository->findById($id);
    //         $newName = new RecipeName($name);
    //         $model->rename($newName);
    //         $this->recipeRepository->update($model);
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
    //         $model = $this->recipeRepository->delete($id);
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

    public function existingRecipeFromApi(): array
    {
        try {
            return $this->recipeRepository->getValueByField('api_id');
        } catch (\Throwable $th) {
            throw new ServiceException(
                message: 'Существующие категории по API не найдены',
                previos: $th
            );
        }
    }
}
