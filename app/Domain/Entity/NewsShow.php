<?
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Id;
class NewsShow{
    private Id $id;
    public function __construct(
        Id $id
    ){
        $this->id = $id;
    }

    /**
     * @param Id $id
     * @return NewsShow
    */
    public function setId(Id $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Id
    */
    public function getId()
    {
        return $this->id;
    }   
}
