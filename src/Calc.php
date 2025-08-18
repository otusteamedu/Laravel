<?php

namespace Konstantin\Calc;

class Calc
{
    public $result;
    public function __construct(){
        $this->result = 0;
    }
    public function plus($arr)
    {        
        foreach($arr as $num){
            $this->result+=(int)$num;
        }
        return $this;
    }

    public function minus($arr)
    {        
        foreach($arr as $num){
            $this->result-=(int)$num;
        }
        return $this;
    }

    public function multi($arr)
    {        
        foreach($arr as $num){
            $this->result*=(int)$num;
        }
        return $this;
    }
    public function div($num)
    {        
        $this->result = $this->result/$num;       
        return $this;
    }

    public function set($num){
        $this->result = $num;
        return $this;
    }

    public function get(){
        return $this->result;
    }
}