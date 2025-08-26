<?php

namespace App\Application\Services\Measure;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Exceptions\ServiceException;
use App\Domain\Factories\Measure\MeasureFactory;
use App\Domain\ValueObjects\Measure\MeasureName;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;

final readonly class MeasureService implements MeasureServiceInterface
{
    private MeasureRepositoryInterface $repository;

    public function __construct(MeasureRepositoryInterface $repository)
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
    //             return (new MeasureDTO($model));
    //         })->toArray();
    //     } catch (\Throwable $th) {
    //         throw new ServiceException(previos:$th);
    //     }
    //     return $models;
    // }

    public function store(string $name, string $lang): void 
    {
        try {
            $model = MeasureFactory::make($name, $lang);
            $this->repository->store($model);
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Запись меры не добавлена',
                previos:$th
            );
        }
    }

    // public function prepairDataForEdit(int $id): MeasureDTO 
    // {
    //     try {
    //         $model = $this->repository->findById($id);
    //         return new MeasureDTO($model);
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
    //         $newName = new MeasureName($name);
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

    public function existingMeasureFromApi(): array 
    {
        try {
            return $this->repository->getValueByField('api_id');
        } catch (\Throwable $th) {
            throw new ServiceException(
                message:'Существующие меры по API не найдены',
                previos:$th
            );
        }
    }
}
