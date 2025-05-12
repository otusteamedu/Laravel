<?php
namespace App\Queries;

use Illuminate\Support\Facades\DB;

class UserQueries
{
    public function __construct(public $qb)
    {
    }
    static function allUsers()
    {
        return DB::table("users");
    }

    function onlyAdmins()
    {
        $this->qb->where('role', 'admin');
        return $this;
    }

    function onlyTop()
    {
        $this->qb->whereBetween('id', [1, 2]);
        return $this;
    }

    function qb()
    {
        return $this->qb;
    }
}
