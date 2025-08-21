<?php

declare(strict_types=1);

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User as DomainUser;

interface UserRepositoryInterface
{
    /**
     * @return DomainUser[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param string $email
     *
     * @return bool
     */
    public function existsByEmail(string $email): bool;
    /**
     * @param int $id
     *
     * @return DomainUser|null
     */
    public function find(int $id): ?DomainUser;

    /**
     * @param DomainUser $user
     *
     * @return DomainUser
     */
    public function save(DomainUser $user): DomainUser;

    /**
     * @param DomainUser $user
     *
     * @return bool|null
     */
    public function delete(DomainUser $user): ?bool;

    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array;

    /**
     * @return DomainUser[]
     */
    public function findSubscribedNews(): array;
}
