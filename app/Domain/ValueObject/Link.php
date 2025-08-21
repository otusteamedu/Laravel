<?
namespace App\Domain\ValueObject;

class Link{
    private string $value;
    public function __construct(string $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Формат ссылки не валидный");
        }
    }
}