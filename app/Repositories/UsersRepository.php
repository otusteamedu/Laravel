<?php

namespace App\Repositories;

use App\Dto\Admin\User\StoreDto;
use App\Dto\Admin\User\UpdateDto;
use App\Dto\User\PasswordDto;
use App\Dto\User\ProfileDto;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UsersRepository
{
    /**
     * @return Collection<array-key, User>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'users.all';
        $ttl = now()->addDay();
        $tag = 'user-list';

        if ($warmup) {
            $users = User::with(['role'])->get();;
            Cache::tags($tag)->put($key, $users, $ttl);
            return $users;
        }

        $users = Cache::tags($tag)->remember($key, $ttl, function () {
            return User::with(['role'])->get();
        });

        return $users;
    }

    /**
     * @return LengthAwarePaginator<array-key, User>
     */
    public function fetchList(string $sort, string $direction, int $page, bool $warmup = false): LengthAwarePaginator
    {
        $key = 'users.' . $sort . '.' . $direction . '.' . $page;
        $ttl = now()->addDay();
        $tag = 'user-list';

        $paginator = Cache::tags($tag)->get($key);

        if (!$paginator || $warmup) {
            $perPage = config('custom.perPageAdmin');
            $paginator = User::orderBy($sort, $direction)->paginate($perPage)->withQueryString();
            Cache::tags($tag)->put($key, $paginator, $ttl);
            return $paginator;
        }

        return $paginator;
    }

    /**
     * @return User
     */
    public function find(int $userId, bool $warmup = false): User
    {
        $key = 'user.' . $userId;
        $ttl = now()->addDay();

        if ($warmup) {
            $user = User::with(['role'])->find($userId);
            if ($user) {
                Cache::put($key, $user, $ttl);
            }
            return $user;
        }

        $user = Cache::remember($key, $ttl, function () use($userId) {
            return User::with(['role'])->find($userId);
        });

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @return int
     */
    public function count(bool $warmup = false): int
    {
        $key = 'users.count';
        $ttl = now()->addDay();

        if ($warmup) {
            $count = User::count();
            Cache::put($key, $count, $ttl);
            return $count;
        }

        $count = Cache::remember($key, $ttl, function () {
            return User::count();
        });

        return $count;
    }

    public function add(StoreDto $storeDto): void
    {
        $user = new User();
        $user->name = $storeDto->name;
        $user->email = $storeDto->email;
        $user->password = $storeDto->password_hash;
        $user->role_id = $storeDto->role_id;
        $user->save();

        Cache::tags('user-list')->flush();
        Cache::forget('users.count');
    }

    public function save(UpdateDto $updateDto): void
    {
        $user = User::find($updateDto->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->name = $updateDto->name;
        $user->email = $updateDto->email;
        $user->role_id = $updateDto->role_id;
        $user->save();

        Cache::forget('user.' . $updateDto->id);
        Cache::tags('user-list')->flush();
    }

    public function saveProfile(ProfileDto $profileDto): void
    {
        $user = User::find($profileDto->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->name = $profileDto->name;
        $user->email = $profileDto->email;
        $user->save();

        Cache::forget('user.' . $profileDto->id);
        Cache::tags('user-list')->flush();
    }

    public function savePassword(PasswordDto $passwordDto): void
    {
        $user = User::find($passwordDto->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->password = $passwordDto->password_hash;
        $user->save();

        Cache::forget('user.' . $passwordDto->id);
        Cache::tags('user-list')->flush();
    }

    public function delete(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            throw new UserNotFoundException();
        }
        
        $user->delete();

        Cache::forget('user.' . $userId);
        Cache::tags('user-list')->flush();
        Cache::forget('users.count');
    }

    public function warmupCache(): void
    {
        $users = $this->fetchAll(true);

        $perPage = config('custom.perPageAdmin');
        $totalPage = ceil(count($users) / $perPage);

        $sorts = config('custom.usersSorts');
        $directions = config('custom.usersDirections');
        $pages = range(1, $totalPage);
        foreach ($sorts as $sort) {
            foreach ($directions as $direction) {
                foreach ($pages as $page) {
                    $this->fetchList($sort, $direction, $page, true);
                }
            }
        }
        
        $ids = $users->pluck('id')->toArray();
        foreach ($ids as $userId) {
            $this->find($userId, true);
        }

        $this->count(true);
    }
}