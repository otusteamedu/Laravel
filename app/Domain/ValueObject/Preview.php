<?
namespace App\Domain\ValueObject;

class Preview{
    private string $value;
    public function __construct(string $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        if (preg_match('/^[а-яА-Яa-zA-Z0-9- ]+$/', $value) && strlen($value)<500) {
            throw new \InvalidArgumentException("Описание может содержать только буквы, цифры, пробел и дефис и быть меньше 500 символов");
        }
    }
}