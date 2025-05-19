<?php

namespace App\Models;

class Photo extends BaseModel
{
    public function recipe() 
    {
        return $this->morphTo('photo');
    }

    public function product() 
    {
        return $this->morphTo('photo');
    }
}
