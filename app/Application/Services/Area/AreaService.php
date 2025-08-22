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
    public AreaRepositoryInterface $areaRepository;

    public function __construct(AreaRepositoryInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function prepairDataForIndex(): array 
    {
        try {
            $areas = $this->areaRepository->getAll();
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
        if (empty($areas)) {
            throw new NotFoundServiceException('Записи отсутствуют.');
        };
        try {
            $areas = collect($areas)->map(function($area) {
                return (new AreaDTO($area));
            })->toArray();
        } catch (\Throwable $th) {
            throw new ServiceException(previos:$th);
        }
        return $areas;
    }

    public function store(string $name, string $lang): void 
    {
        try {
            $area = AreaFactory::make($name, $lang);
            $this->areaRepository->store($area);
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
            $area = $this->areaRepository->findById($id);
            return new AreaDTO($area);
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
            $area = $this->areaRepository->findById($id);
            $newName = new AreaName($name);
            $area->rename($newName);
            $this->areaRepository->update($area);
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
            $area = $this->areaRepository->delete($id);
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
}
