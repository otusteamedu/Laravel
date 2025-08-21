<?
namespace App\Application\UseCase;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\Entity\NewsDelete;
class DeleteNewsUseCase{
    private NewsRepositoryInterface $newsRepository;
    /**
     * @param NewsRepositoryInterface $newsRepository
    */
    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }

    public function execute($id){ 
        $news = new NewsDelete(
            new Id($id)
        );
        $this->newsRepository->delete($news);
    }
}