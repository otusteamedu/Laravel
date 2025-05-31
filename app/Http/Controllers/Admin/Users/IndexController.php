<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\Handlers\IndexHandler;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Cписок пользователей
     */
    public function __invoke(Request $request, IndexHandler $handler): View
    {
        $perPage = $request->input('per_page', 10);
        $users = $handler($perPage);
        return view('admin.users.index', compact('users'));
    }
}
