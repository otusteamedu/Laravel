<?php
namespace App\Infrastructure;

class SettingDTO{
    private $object,$inc;
    public function __construct($object)
    {
        $this->object = $object['model'];
        $this->inc = $object['inc'];
    }
    public function all(){
        return $this->object::all();
    }
    public function find($id){
        return $this->object::findOrFail($id);
    }
    public function where($where){
        foreach($where as $key=>$value){
            $arr[] = [$key,$value];
        }
        return $this->object::where($arr)->first();
    }
    public function whereIn($field,$arr){
        return $this->object::whereIn($field,$arr)->get();
    }
    public function create($arr){
        return $this->object::create($arr);
    }
    public function update($arr){
        return $this->object::update($arr);
    }
    public function delete($id){
        $delete = $this->object::find($id);
        return $delete->delete();
    }

    public function for($property,$return){
        $result = [];
        $inc = $this->inc;
        if(isset($return->$inc)){
            foreach($property as $key=>$prop){
                if(isset($return->$prop)){
                    $result[$key] = $return->$prop;
                }
            }
        }
        else{
            foreach($return as $item=>$arr){
                foreach($property as $key=>$prop){
                    if(isset($arr->$prop)){
                        $result[$item][$key] = $arr->$prop;
                    }
                }
            }
        }        
        return $result;
    }
    public function list($offset,$count){
        $offset = $offset*$count;
        return $this->object::skip($offset)->take($count)->get();
    }
}