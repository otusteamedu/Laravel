<?php

namespace App\Repositories;

use App\DbQueries\PostTableQueries;

class PostRepo
{
    public function getTrickyPosts()
    {
        $postsRequest = new PostTableQueries();

        $req = $postsRequest->adminsOnly()->newOnly()->getQuery();

        $posts = $req->first();

        return $posts;
    }
}
