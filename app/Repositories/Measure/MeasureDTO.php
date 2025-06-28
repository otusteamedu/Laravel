<?php

namespace App\Repositories\Measure;

use App\Models\Measure;

class MeasureDTO 
{
    public int $id;
    public string $name;
    public string $created_at;

    public function __construct(Measure $measure)
    {
        $this->id = $measure->getId();
        $this->name = $measure->getName();
        $this->created_at = $measure->getCreatedAt();
    }

    public function toArray() 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at
        ];
    }
}
