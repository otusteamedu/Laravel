<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllTasks\Query;
use App\Services\Queries\FetchAllTasks\Fetcher;

class IndexController extends Controller
{
    /**
     * Показать список задач
     */
    public function index(Fetcher $fetcher)
    {
        $query = new Query(perPage: 10);
        $tasks = $fetcher->fetch($query);

        return view('admin.tasks.index', compact('tasks'));
    }
} 