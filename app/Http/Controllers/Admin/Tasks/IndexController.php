<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\Handlers\IndexHandler;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Отобразить список задач
     */
    public function __invoke(Request $request, IndexHandler $handler): View
    {
        $perPage = $request->input('per_page', 10);
        $tasks = $handler($perPage);
        return view('admin.tasks.index', compact('tasks'));
    }
} 