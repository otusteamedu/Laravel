<?
namespace App\Application\UseCase;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Count;
use App\Domain\ValueObject\Page;
use App\Domain\Entity\NewsIndex;
class IndexNewsUseCase{
    private NewsRepositoryInterface $newsRepository;
    /**
     * @param NewsRepositoryInterface $newsRepository
    */
    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }

    public function execute($page,$count){ 
        $news = new NewsIndex(
            new Count($count),
            new Page($page)            
        );
        return $this->newsRepository->list($news);
    }
}