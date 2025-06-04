<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllUsers\Query;
use App\Services\Queries\FetchAllUsers\Fetcher;

class IndexController extends Controller
{
    /**
     * Показать список пользователей
     */
    public function index(Fetcher $fetcher)
    {
        $query = new Query(perPage: 10);
        $users = $fetcher->fetch($query);

        return view('admin.users.index', compact('users'));
    }
}
