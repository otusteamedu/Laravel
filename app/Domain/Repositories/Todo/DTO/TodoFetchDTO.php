<?php

namespace App\Domain\Repositories\Todo\DTO;

use Carbon\Carbon;
use App\Domain\Repositories\User\DTO\UserDTO;
use App\Domain\Repositories\Todo\DTO\TodoStatusDTO;

final readonly class TodoFetchDTO
{
    /**
     * Summary of __construct
     * @param string $title
     * @param UserDTO $author
     * @param TodoStatusDTO $status
     * @param string $description
     * @param Carbon $deadline
     * @param Carbon|null $created
     * @param Carbon|null $updated
     * @param array|null $options
     * @param int|null $todoId
     */
    public function __construct(
        public string  $title,
        public UserDTO $author,
        public TodoStatusDTO $status,
        public string  $description,
        public Carbon  $deadline,
        public ?Carbon $created = null,
        public ?Carbon $updated = null,
        public ?array  $options = null,
        public ?int    $todoId = null,
    ) {}
}
