<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Показать дашборд админ-панели
     */
    public function index()
    {
        $stats = [
            'users' => 99,
            'categories' => 99,
            'tasks' => 99,
            'recent_tasks' => [],
            'recent_users' => [],
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
