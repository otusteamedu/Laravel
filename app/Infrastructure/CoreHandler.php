<?php
namespace App\Infrastructure;

class CoreHandler extends CoreHandlerInterface
{
    public $domain,$method,$essence;
    public function __construct($domain,$method,$essence) {
        $this->domain = $domain;
        $this->method =$method;
        $this->essence = [
            'method'=>'App\Services\Domain\Methods\Essence\\'.$essence.'Service',
            'domain'=> 'App\Services\Domain\Agregators\Essence\\'.$essence.'Service'
        ];
        $this->start();
    }
    private function start(){
        $this->method = $this->isClass('method');
        $this->domain = $this->isClass('domain');
    }
    private function isClass($name){ 
        $class = $this->$name;       
        if(class_exists($class)){
            return new $class($this->essence[$name]);
        }
        return false;
    }
    public function set(){
        return $this;
    }
}
