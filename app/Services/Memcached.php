<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;

class Memcached
{
    public static function get($json="[]"){
        $arr = json_decode($json,true);
        return $arr;
    }

    public static function set($arr=[]){
        $json = json_encode($arr,true);
        return $json;
    }

    public static function delete($key,$tags){
        $cache = Cache::get($key);
        if (is_null($cache)) {
            return false;
        }
        $arr = self::get($cache); 
        if(is_array($arr) && isset($arr[$tags])){
            unset($arr[$tags]);
            $json = self::set($arr);
            Cache::set($key, $json);
            return $arr;
        }
        return false;
    }
}