<?
namespace App\Application\UseCase;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\Entity\NewsShow;
class ShowNewsUseCase{
    private NewsRepositoryInterface $newsRepository;
    /**
     * @param NewsRepositoryInterface $newsRepository
    */
    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }

    public function execute($id){ 
        $news = new NewsShow(
            new Id($id)
        );
        return $this->newsRepository->show($news);
    }
}