<?
namespace App\Infrastructure;

interface InterfaceDTO{
    public function all();
    public function find($id);
    public function where($where);
    public function whereIn($field,$arr);
    public function create($arr);
    public function update($arr);
    public function delete($id);
    public function for($property,$return);
    public function list($offset,$count);
}