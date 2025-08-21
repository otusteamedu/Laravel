<?php

namespace App\Infrastructure\Eloquent\Repositories\Users;

use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User as EloquentUser;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return DomainUser[]
     */
    public function fetchAll(): array
    {
        $models = EloquentUser::all();
        return array_map([UserMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return DomainUser[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        $models = EloquentUser::orderBy('id', 'desc')
                              ->limit($limit)
                              ->offset($offset)
                              ->get();

        return array_map([UserMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return EloquentUser::count();
    }

    /**
     * @param int $id
     * @return DomainUser|null
     */
    public function find(int $id): ?DomainUser
    {
        $model = EloquentUser::find($id);
        return $model ? UserMapper::toEntity($model) : null;
    }

    /**
     * @param DomainUser $user
     * @return DomainUser
     */
    public function save(DomainUser $user): DomainUser
    {
        $model = UserMapper::toModel($user);
        $model->save();
        return UserMapper::toEntity($model);
    }

    /**
     * @param DomainUser $user
     * @return bool|null
     */
    public function delete(DomainUser $user): ?bool
    {
        $model = EloquentUser::find($user->getId());
        return $model ? $model->delete() : null;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function existsByEmail(string $email): bool
    {
        return EloquentUser::where('email', $email)->exists();
    }

    /**
     * @param int[] $ids
     * @return DomainUser[]
     */
    public function findByIds(array $ids): array
    {
        $models = EloquentUser::whereIn('id', $ids)->get();
        return array_map([UserMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @return DomainUser[]
     */
    public function findSubscribedNews(): array
    {
        $models = EloquentUser::where('subscribed_news', true)->get();
        return array_map([UserMapper::class, 'toEntity'], $models->all());
    }
}
