<?
namespace App\Application\UseCase;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Text;
use App\Domain\ValueObject\Link;
use App\Domain\ValueObject\Preview;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\CreateAt;
use App\Domain\Entity\NewsCreate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
class CreateNewsUseCase{
    private NewsRepositoryInterface $newsRepository;
    /**
     * @param NewsRepositoryInterface $newsRepository
    */
    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }

    public function execute($arr){
        $user = User::latest()->first();
        $userId = Auth::id() ?? $user->id; 
        $news = new NewsCreate(
            new UserId($userId),
            new CreateAt(Carbon::now()->format('Y-m-d')),
            new Name($arr['name']),
            new Text($arr['text']),
            new Preview(Str::limit($arr['text'], 20, '...')),
            new Link(Str::slug($arr['name']))
        );
        $this->newsRepository->create($news);
    }
}