<?php
namespace App\Services\Tasks\Results;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

final class TaskDTO
{
    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $due_date;

    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $created_at;

    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $updated_at;

    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly int $executor_id,
        public readonly string $executor_name,
        public readonly int $category_id,
        public readonly string $category_name,
        public readonly string $category_color,
        public readonly int $priority_id,
        public readonly string $priority_name,
        public string $status = 'новая',
        ?string $due_date = null,
        ?string $created_at = null,
        ?string $updated_at = null,
    ) {
        // Преобразуем строки дат в объекты Carbon
        $this->due_date = $due_date ? Date::parse($due_date) : null;
        $this->created_at = $created_at ? Date::parse($created_at) : null;
        $this->updated_at = $updated_at ? Date::parse($updated_at) : null;
    }
}
