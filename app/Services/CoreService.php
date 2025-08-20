<?php

namespace App\Services;
use App\Infrastructure\CoreHandler;
use App\Infrastructure\CoreHandlerInterface;
class CoreService extends CoreHandlerInterface
{

    private $append,$prepend;
    public $domain,$method,$essence;
    public function __construct($domain,$method,$essence){
        $this->append = 'Service';
        $this->prepend = 'App\Services\Domain\\';
        $this->domain = ['method'=>$domain,'dir'=>'Agregators'];
        $this->method = ['method'=>$method,'dir'=>'Methods'];
        $domain = $this->wrapper('domain');
        $method = $this->wrapper('method');
        $handler = new CoreHandler($domain,$method,$essence);
        $set = $handler->set();
        $this->domain = $set->domain;
        $this->method = $set->method;
    }
    private function wrapper($property){
        return $this->prepend.$this->$property['dir'].'\\'.$this->$property['method'].$this->append;
    }

    public function set(){
        return $this;
    }
}