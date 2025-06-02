<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Services\User\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    /**
     * @return array
     */
    public function fetchAll(): array {
        return $this->user::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return $this->user->query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function find(int $id): ?User {
        return $this->user::query()->find($id);
    }

    /**
     * @return User
     */
    public function create(): User {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function save(User $user): bool {
        return $user->save();
    }

    /**
     * @param User $user
     *
     * @return bool|null
     */
    public function delete(User $user): ?bool {
        return $user->delete();
    }


    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array
    {
        return $this->user::query()->whereIn('id', $ids)->get()->keyBy('id')->all();
    }
}
