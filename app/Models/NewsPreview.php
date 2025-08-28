<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsPreview extends Model
{
    public bool $timestamps = false;
    /**
    @return belongsTo<News>
    */
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}