<?
namespace App\Domain\ValueObject;

class Text{
    private string $value;
    public function __construct(string $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        if (strlen($value)>1200) {
            throw new \InvalidArgumentException("Описание может содержать только буквы, цифры, пробел и дефис и быть меньше 1200 символов");
        }
    }
}