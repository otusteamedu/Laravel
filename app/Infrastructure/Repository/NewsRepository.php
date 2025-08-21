<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Models\News as EloquentNews;
use App\Domain\Entity\News as DomainNews;
use App\Domain\Entity\NewsUpdate as DomainNewsUpdate;
use App\Domain\Entity\NewsCreate as DomainNewsCreate;
use App\Domain\Entity\NewsDelete as DomainNewsDelete;
use App\Domain\Entity\NewsIndex as DomainNewsIndex;
use App\Domain\Entity\NewsShow as DomainNewsShow;
use App\Domain\ValueObject\Id;
class NewsRepository implements NewsRepositoryInterface
{
    private array $entity = [];
    private $enews;
    public function __construct(){
        $this->enews = new EloquentNews();
    }
    public function create(DomainNewsCreate $entity){
        $this->enews->user_id = $entity->getUserId()->getValue();
        $this->enews->create_at = $entity->getCreateAt()->getValue();
        $this->enews->name = $entity->getName()->getValue();
        $this->enews->text = $entity->getText()->getValue();
        $this->enews->link = $entity->getLink()->getValue();
        $this->enews->preview = $entity->getPreview()->getValue();        
        $this->enews->save();        
    }

    public function delete(DomainNewsDelete $news){
        $id = $news->getId()->getValue();
        $this->enews::query()->find($id)->delete();
    }

    public function show(DomainNewsShow $news){
        $id = $news->getId()->getValue();
        return $this->enews::query()->find($id);
    }

    public function update(DomainNewsUpdate $entity){
        $enews = $this->enews->query()->find($entity->getId()->getValue());
        $enews->name = $entity->getName()->getValue();
        $enews->text = $entity->getText()->getValue();
        $enews->save();
    }
    public function list(DomainNewsIndex $entity){
        $page = $entity->getPage()->getValue();
        $thiscount = $entity->getCount()->getValue();
        $offset = (int)$page*(int)$thiscount;
        $count = ceil($this->enews::count()/$thiscount);
        $pagination = ['count'=>$count,'page'=>$page];
        return ['news'=>$this->enews::skip($offset)->take($thiscount)->get(),'pagination'=>$pagination];
    }
}
