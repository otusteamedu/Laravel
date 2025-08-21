<?
namespace App\Domain\ValueObject;

class Name{
    private string $value;
    public function __construct(string $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        if (strlen($value)>200) {
            throw new \InvalidArgumentException("Название может содержать только буквы, цифры, пробел и дефис и быть меньше 200 символов");
        }
    }
}