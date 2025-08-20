<?php

namespace App\Services\Domain\Agregators;

class ContentService implements AgregatorsInterfaceService{
    private $object,$setting;
    public function __construct($essence){
        $this->object = new $essence;
    }
    public function set($setting){
        $this->setting = $setting;
    }
    public function data(){
        $model = $this->setting->model;
        $request = $this->setting->settingDomain[$model]['request'];
        $id = $request['id'];
        $count = $request['count'];
        $page = $request['page'];
        $property = $this->object->property();
        if($id!=null){
            return ['method'=>'find','arg'=>[$id],'property'=>$property];
        }
        else{
            return ['method'=>'list','arg'=>[$page,$count],'property'=>$property];
        }
    }
    public function model(){
        return $this->object->model();
    }
}