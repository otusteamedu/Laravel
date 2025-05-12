<?php
namespace App\Repo;

use App\Queries\UserQueries;

class UserRepo
{
    public static function getTopUsers()
    {
        return (new UserQueries(UserQueries::allUsers()))->onlyTop()->qb()->get();
    }

    public static function createUser(CreateUserDTO $userDTO): Returntype
    {
        // insert table query
    }
}
