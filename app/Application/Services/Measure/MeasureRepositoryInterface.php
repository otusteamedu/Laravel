<?php

namespace App\Application\Services\Measure;

use App\Domain\BusinessModels\Measure;
use App\Domain\ValueObjects\Lang;

interface MeasureRepositoryInterface
{
    /**
     * @return array <int, Measure>
     */
    // public function getAll(): array;

    public function store(Measure $model): void;

    public function findById(int $id): Measure;

    public function findByName(string $name, Lang $lang): Measure;

    // public function update(Measure $category, ?string $lang = null): void;

    // public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
