<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


class MainAppStartPageController extends Controller
{
    public function index()
    {
        return view('mainAppPage', ['mainAppUserId' => Auth::user()->id ?? null]);
    }
}
