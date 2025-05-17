<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\PageDataProviders\TodoListDataProfider;

class TodoController extends Controller
{
    public function list(Request $request)
    {
        $data = new TodoListDataProfider($request, Auth::user());

        return view('user.todos', compact('data'));
    }
}
