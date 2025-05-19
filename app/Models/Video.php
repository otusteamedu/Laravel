<?php

namespace App\Models;

class Video extends BaseModel
{
    public function recipe() 
    {
        return $this->morphTo('video');
    }
}
