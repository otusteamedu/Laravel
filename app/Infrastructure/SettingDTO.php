<?php
namespace App\Infrastructure;

class SettingDTO implements InterfaceDTO{
    private $dto;
    public function __construct($object)
    {
        $this->dto = new DTO\Model($object);
    }
    public function all(){
        return $this->dto->all();
    }
    public function find($id){
        return $this->dto->find($id);
    }
    public function where($where){
        return $this->dto->where($where);
    }
    public function whereIn($field,$arr){
        return $this->dto->whereIn($field,$arr);
    }
    public function create($arr){
        return $this->dto->create($arr);
    }
    public function update($arr){
        return $this->dto->update($arr);
    }
    public function delete($id){
        return $this->dto->delete($id);
    }

    public function for($property,$return){
        return $this->dto->for($property,$return);
    }
    public function list($offset,$count){
        return $this->dto->list($offset,$count);
    }
}