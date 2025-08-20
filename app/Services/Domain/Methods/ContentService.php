<?php

namespace App\Services\Domain\Methods;

class ContentService implements MethodsInterfaceService{
    private $object,$setting;
    public function __construct($essence){
        $this->object = new $essence;
    }
    public function set($setting){
        $this->setting = $setting;
    }
    public function method($data){
        $method = $data['method'];
        $arg = $data['arg'];
        $property = $data['property'];
        $return = $this->$method(...$arg);
        return $this->setting->dto->for($property,$return);
    }
    private function find($id){
        return $this->setting->dto->find($id);
    }
    private function list($page,$count){
        return $this->setting->dto->list($page,$count);    
    }
}