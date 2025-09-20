<?php

namespace App\Application\Services\Area;

use App\Domain\BusinessModels\Area;
use App\Domain\ValueObjects\Lang;

interface AreaRepositoryInterface
{
    /**
     * @return array <int, Area>
     */
    public function getAll(): array;

    public function store(Area $area): void;

    public function findById(int $id): Area;

    public function findByName(string $name, Lang $lang): Area;

    public function update(Area $area, ?string $lang = null): void;

    public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
