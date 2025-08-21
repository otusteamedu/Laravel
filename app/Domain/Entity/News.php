<?
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Text;
use App\Domain\ValueObject\Preview;
use App\Domain\ValueObject\Photo;
use App\Domain\ValueObject\Link;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\CreateAt;
class News{
    private Id $id;
    private Name $name;
    private Text $text;
    private UserId $userId;
    private CreateAt $createAt;
    private Preview $preview;
    private Photo $photo;
    private Link $link;
    public function __construct(
        Id $id,
        UserId $userId,
        CreateAt $createAt,
        Name $name,
        Text $text,
        Preview $preview,
        Photo $photo,
        Link $link
    ){
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->userId = $userId;
        $this->createAt = $createAt;
        $this->preview = $preview;
        $this->photo = $photo;
        $this->link = $link;
    }

    /**
     * @param UserId $name
     * @return News
    */
    public function setUserId(Name $name)
    {
        $this->userId = $name;
        return $this;
    }
    /**
     * @param CreateAt $name
     * @return News
    */
    public function setCreateAt(Name $name)
    {
        $this->createAt = $name;
        return $this;
    }
    /**
     * @param Name $name
     * @return News
    */
    public function setName(Name $name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param Text $text
     * @return News
    */
    public function setText(Text $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param Preview $preview
     * @return News
    */
    public function setPreview(Preview $preview)
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * @param Photo $photo
     * @return News
    */
    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param Link $link
     * @return News
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
     * @return Preview
    */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @return Photo
    */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return Link
    */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return Id
    */
    public function getId()
    {
        return $this->id;
    }

   
}
