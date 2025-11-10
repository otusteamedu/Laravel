<?php

namespace App\DbQueries;

use DB;

class PostTableQueries
{
    private $query;
    public function __construct()
    {
        $this->query = DB::table('posts');
    }
    function adminsOnly()
    {
        $this->query->where('user_id', '2');
        return $this;
    }

    function newOnly()
    {
        $this->query->where('title', 'LIKE', '%new%');
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
