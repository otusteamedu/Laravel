<?php

namespace Tariff\Http\Controllers;

use Illuminate\Routing\Controller;

class TariffController extends Controller
{
    public function index()
    {
        return view('tariff::index');  
    }
}
