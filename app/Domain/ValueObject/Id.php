<?
namespace App\Domain\ValueObject;

class Id{
    private int $value;
    public function __construct(int $value){
        $this->assertIdIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIdIsValid(int $value){
        if($value<=0){
            throw new \InvalidArgumentException("Идентификатор должен быть натуральным числом");
        }
    }
}