<?php
namespace App\Services\Users\Results;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

final class UserDTO
{
    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $created_at;

    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $updated_at;

    /**
     * @var Carbon|null
     */
    public readonly ?Carbon $email_verified_at;

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        ?string $created_at = null,
        ?string $updated_at = null,
    ) {
        // Преобразуем строки дат в объекты Carbon
        $this->created_at = $created_at ? Date::parse($created_at) : null;
        $this->updated_at = $updated_at ? Date::parse($updated_at) : null;
    }
}
