<?
namespace App\Domain\Repository;
use App\Domain\Entity\NewsUpdate;
use App\Domain\Entity\NewsCreate;
use App\Domain\Entity\NewsDelete;
use App\Domain\Entity\NewsShow;
use App\Domain\Entity\NewsIndex;
interface NewsRepositoryInterface{
    public function show(NewsShow $news);
    public function update(NewsUpdate $news);
    public function create(NewsCreate $news);
    public function delete(NewsDelete $news);
    public function list(NewsIndex $news);
}