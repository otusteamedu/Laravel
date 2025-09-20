<?php

namespace App\Application\Services\Category;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Domain\Factories\Category\CategoryFactory;
use App\Domain\ValueObjects\Category\CategoryName;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;

final readonly class CategoryService implements CategoryServiceInterface
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // public function prepairDataForIndex(): array 
    // {
    //     try {
    //         $models = $this->repository->getAll();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     if (empty($models)) {
    //         throw new NotFoundServiceException('Записи отсутствуют.');
    //     };
    //     try {
    //         $models = collect($models)->map(function($model) {
    //             return (new CategoryDTO($model));
    //         })->toArray();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     return $models;
    // }

    public function store(string $name, string $description, string $apiId, string $lang): void 
    {
        try {
            $model = CategoryFactory::make($name, $description, $lang, $apiId);
            $this->repository->store($model);
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Запись категории не добавлена',
                previos:$th
            );
        }
    }

    // public function prepairDataForEdit(int $id): CategoryDTO 
    // {
    //     try {
    //         $model = $this->repository->findById($id);
    //         return new CategoryDTO($model);
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
    //         $model = $this->repository->findById($id);
    //         $newName = new CategoryName($name);
    //         $model->rename($newName);
    //         $this->repository->update($model);
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
    //         $model = $this->repository->delete($id);
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

    public function existingCategoryFromApi(): array 
    {
        try {
            return $this->repository->getValueByField('api_id');
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Существующие категории по API не найдены',
                previos:$th
            );
        }
    }
}
