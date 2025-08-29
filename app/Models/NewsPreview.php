<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
class NewsPreview extends Model
{
    public bool $timestamps = false;
    /**
    @return belongsTo<News>
    */
    public function news()
    {
        $class = Relation::getMorphedModel('news');
        return $this->belongsTo($class);
    }
}