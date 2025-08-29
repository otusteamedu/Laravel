<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Test extends Controller
{
    public function test(){
        $result = DB::table('users')->inRandomOrder()->first()->id;
        dd($result);
    }
}
