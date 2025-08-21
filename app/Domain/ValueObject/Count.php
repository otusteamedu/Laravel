<?
namespace App\Domain\ValueObject;

class Count{
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
            throw new \InvalidArgumentException("Количество должно быть натуральным числом");
        }
    }
}