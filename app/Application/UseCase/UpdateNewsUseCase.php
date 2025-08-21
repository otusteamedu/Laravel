<?
namespace App\Application\UseCase;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Text;
use App\Domain\ValueObject\Id;
use App\Domain\Entity\NewsUpdate;
class UpdateNewsUseCase{
    private NewsRepositoryInterface $newsRepository;
    /**
     * @param NewsRepositoryInterface $newsRepository
    */
    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }

    public function execute($arr,$id){
        $news = new NewsUpdate(
            new Id($id),
            new Name($arr['name']),
            new Text($arr['text'])
        );
        $this->newsRepository->update($news);
    }
}