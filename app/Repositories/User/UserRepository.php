<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Services\User\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return array
     */
    public function fetchAll(): array {
        return User::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return User::query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function find(int $id): ?User {
        return User::query()->find($id);
    }

    /**
     * @return User
     */
    public function create(): User {
        return new User;
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
        return User::query()->whereIn('id', $ids)->get()->keyBy('id')->all();
    }

    /**
     * @return array
     */
    public function findSubscribedNews(): array
    {
        return User::query()->where('subscribed_news', true)->get()->all();
    }
}
