<?
namespace App\Services\Repositories;
use App\Models\News;
interface NewsRepositoryInterface{
    public function fetchAll();

    public function find(int $id);

    public function save(News $News);

    public function add(News $News);
    public function fetchByAuthor(int $authorId);
}
