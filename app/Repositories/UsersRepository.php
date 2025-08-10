<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Services\UsersRepositoryInterface;

class UsersRepository implements UsersRepositoryInterface
{
    public function fetchAll(): array
    {
        return User::all()->all();
    }

    public function find(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function save(User $post): void
    {
        $post->save();
    }

    public function add(User $post): void
    {
        $post->save();
    }

    /**
     * @return User[]
     */
    public function fetchByAuthor(int $authorId): array
    {
        return User::query()
            ->where('author_id', $authorId)
            ->get()
            ->all()
        ;
    }

    public function findByIds(array $ids): array
    {
        return User::query()
            ->whereIn('id', $ids)
            ->get()
            ->all()
        ;
    }
}
