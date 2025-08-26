<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('admin.dashboard');
    }
}
