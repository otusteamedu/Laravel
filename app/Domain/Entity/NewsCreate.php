<?
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Text;
use App\Domain\ValueObject\Preview;
use App\Domain\ValueObject\Link;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\CreateAt;
class NewsCreate{
    private UserId $userId;
    private CreateAt $createAt;
    private Name $name;
    private Text $text;
    private Preview $preview;
    private Link $link;
    public function __construct(
        UserId $userId,
        CreateAt $createAt,
        Name $name,
        Text $text,
        Preview $preview,
        Link $link
    ){
        $this->userId = $userId;
        $this->createAt = $createAt;
        $this->name = $name;
        $this->text = $text;
        $this->preview = $preview;
        $this->link = $link;
    }


    /**
     * @param Name $name
     * @return NewsCreate
    */
    public function setName(Name $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param UserId $name
     * @return NewsCreate
    */
    public function setUserId(Name $name)
    {
        $this->userId = $name;
        return $this;
    }
/**
     * @param CreateAt $name
     * @return NewsCreate
    */
    public function setCreateAt(Name $name)
    {
        $this->createAt = $name;
        return $this;
    }
    /**
     * @param Text $text
     * @return NewsCreate
    */
    public function setText(Text $text)
    {
        $this->text = $text;
        return $this;
    }

     /**
     * @param Preview $preview
     * @return NewsCreate
    */
    public function setPreview(Preview $preview)
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * @param Link $link
     * @return NewsCreate
    */
    public function setLink(Link $link)
    {
        $this->link = $link;
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
     * @return Preview
    */
    public function getPreview()
    {
        return $this->preview;
    }
    /**
     * @return UserId
    */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * @return CreateAt
    */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @return Link
    */
    public function getLink()
    {
        return $this->link;
    }
   
}
