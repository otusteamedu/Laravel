<?php
namespace App\Services\Categories\Results;
use Illuminate\Pagination\LengthAwarePaginator;
final readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color,
        public string $description,
        public int $tasks_count = 0,
    ) {
    }
}
