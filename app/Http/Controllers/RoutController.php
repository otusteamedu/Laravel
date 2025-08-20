<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoreService;
use App\Infrastructure\SettingRout;
use App\Infrastructure\SettingDTO;
class RoutController
{
    public $result,$dto,$settingDomain,$model;
    public function index($domain,$method,$essence,Request $request){  
        $service = new CoreService($domain,$method,$essence);
        $this->result = $service->set();
        if(!$this->result->method || !$this->result->domain){
            abort(404);
        }
        else{                  
            $setting = new SettingRout;
            $this->settingDomain = $setting->start($request);
            $this->model = $this->result->domain->model();
            $this->dto = new SettingDTO($this->settingDomain[$this->model]);
            $this->set($this);
            $data = $this->result->domain->data();
            return $this->result->method->method($data);
        }
    }

    private function set($settingDomain){
        $this->result->method->set($settingDomain);
        $this->result->domain->set($settingDomain);
    }




}
