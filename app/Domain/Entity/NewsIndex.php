<?
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Count;
use App\Domain\ValueObject\Page;
class NewsIndex{
    private Count $count;
    private Page $page;
    public function __construct(
        Count $count,
        Page $page
    ){
        $this->count = $count;
        $this->page = $page;
    }

    /**
     * @param Count $count
     * @return NewsIndex
    */
    public function setCount(Count $count)
    {
        $this->count = $count;
        return $this;
    }
    /**
     * @param Page $page
     * @return NewsIndex
    */
    public function setPage(Page $page)
    {
        $this->page = $page;
        return $this;
    }
    /**
     * @return Count
    */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return Page
    */
    public function getPage()
    {
        return $this->page;
    }

   
}
