<?php

namespace App\Modules\ISS\src\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\ISS\src\Models\EducationRoute;

class EducationRouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var EducationRoute $educationRoute */
        $educationRoute = $this->resource;

        //return parent::toArray($request);
        return [
            'routeMarker' => 'iss',
            'id' => $educationRoute->id,
            'name' => $educationRoute->name,
            'createDate' => $educationRoute->created_at,
            'updateDate' => $educationRoute->updated_at,
            'deleteDate' => $educationRoute->deleted_at,
        ];
    }

    public function with(Request $request)
    {
        return ['meta' => ['ISS module api v1']];
    }
}
