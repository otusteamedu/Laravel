<?
namespace App\Domain\ValueObject;

class CreateAt{
    private string $value;
    public function __construct(string $value){
        $this->assertIsValid($value);
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
    private function assertIsValid(string $value){
        
    }
}