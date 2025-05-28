<?php

namespace App\Modules\ISS\src\Http\Controllers;

class IssStartPageController extends Controller
{
   public function index()
   {
       return view('iss::issMainPage');
   }
}
