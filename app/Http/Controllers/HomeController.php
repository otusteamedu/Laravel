<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * @return View
     */
   public function __invoke(): View
   {
       return view('home');
   }
}
