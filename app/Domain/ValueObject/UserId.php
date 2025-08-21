<?
namespace App\Domain\ValueObject;

class UserId{
    private int $value;
    public function __construct(int $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        if ($value<0) {
            throw new \InvalidArgumentException("UserId должно быть больше нуля");
        }
    }
}