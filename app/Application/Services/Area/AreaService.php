<?php

namespace App\Application\Services\Area;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Domain\Factories\Area\AreaFactory;
use App\Domain\ValueObjects\Area\AreaName;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

final readonly class AreaService implements AreaServiceInterface
{
    public AreaRepositoryInterface $repository;

    public function __construct(AreaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function prepairDataForIndex(): array 
    {
        try {
            $models = $this->repository->getAll();
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
        if (empty($models)) {
            throw new NotFoundServiceException('Записи отсутствуют.');
        };
        try {
            $models = collect($models)->map(function($model) {
                return (new AreaDTO($model));
            })->toArray();
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
        return $models;
    }

    public function store(string $name, string $lang): void 
    {
        try {
            $model = AreaFactory::make($name, $lang);
            $this->repository->store($model);
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Запись не добавлена',
                previos:$th
            );
        }
    }

    public function prepairDataForEdit(int $id): AreaDTO 
    {
        try {
            $model = $this->repository->findById($id);
            return new AreaDTO($model);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundServiceException(
                message:'Запись не найдена для редактирования'
            );
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
    }
    
    public function update(int $id, string $name): void 
    {
        try {
            $model = $this->repository->findById($id);
            $newName = new AreaName($name);
            $model->rename($newName);
            $this->repository->update($model);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundServiceException(
                message:'Запись не найдена для редактирования'
            );
        } catch (QueryException $e) {
            throw new ServiceException(
                message:'Запись не сохранена',
                previos:$e
            );
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
    }

    public function delete(int $id): void 
    {
        try {
            $model = $this->repository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundServiceException(
                message:'Запись не найдена для удаления'
            );
        } catch (QueryException $e) {
            throw new ServiceException(
                message:'Запись не удалена',
                previos:$e
            );
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
    }

    public function existingAreaFromNameEn(): array 
    {
        try {
            return $this->repository->getValueByField('name_en');
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Существующие территории по название не найдены',
                previos:$th
            );
        }
    }
}
