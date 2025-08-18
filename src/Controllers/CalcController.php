<?php

namespace Konstantin\Calc\Controllers;

use Illuminate\Http\Request;
class CalcController {
    public static $num;
    /*
    Example:$result = $calc->set($num)->minus([5,2])->plus([5])->div(2)->get();
    */
    public function calc(Request $request){        
        $post = $request->all();
        if(isset($post['num2']) && isset($post['num']) && isset($post['method'])){
            $num = (float)$post['num'];
            $num2 = (float)$post['num2'];
            $method = (string)$post['method'];
            $calc = new \Konstantin\Calc\Calc();
            if(is_null(self::$num)){
                $calc = $calc->set($num);
                self::$num = $num;
            }
            $result = 0;
            if(method_exists($calc,$method)){
                if($method!='div'){
                    $num2 = [$num2];
                }
                
                $result = $calc->$method($num2); 
                $result = $result->get();
            }        
            return view('calc::calc', ['calc'=>$result]);
        }
        return view('calc::calc', ['calc'=>0]);
    }
    public function eval(Request $request){
        $post = $request->all();
        $result = eval("return " . $post['result'] . ";");
        return $result;
    }
}
