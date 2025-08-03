<?php

namespace App\Modules\ISS\src\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Modules\ISS\src\Http\Resources\EducationRouteResource;


class EducationRouteResourceCollection extends ResourceCollection
{
    public $collects = EducationRouteResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return ['data' => $this->collection];
    }
}
