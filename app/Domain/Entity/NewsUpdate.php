<?
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Text;
class NewsUpdate{
    private Id $id;
    private Name $name;
    private Text $text;
    public function __construct(Id $id,
    Name $name,
    Text $text){
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * @param Name $name
     * @return NewsUpdate
    */
    public function setName(Name $name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param Text $text
     * @return NewsUpdate
    */
    public function setText(Text $text)
    {
        $this->text = $text;
        return $this;
    }


    /**
     * @return Name
    */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Text
    */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return Id
    */
    public function getId()
    {
        return $this->id;
    }   
}
