<?php

namespace ISS\App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use ISS\App\Infrastructure\Models\UserData;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var UserData $userData */
        $userData = $this->resource;

        //return parent::toArray($request);
        return [
            'userMarker' => 'iss',
            'id' => $userData->id,
            'userId' => $userData->user_id,
            'roleId' => $userData->role_id,
            'userIssAvatarPath' => $userData->user_iss_avatar_path,
            'organization' => $userData->organization,
            'name' => $userData->name,
            'lastName' => $userData->last_name,
            'secondName' => $userData->second_name,
            'email' => $userData->email,
            'webToken' => $userData->web_token,
            'createDate' => $userData->created_at,
            'updateDate' => $userData->updated_at,
            'deleteDate' => $userData->deleted_at,
        ];
    }

    public function with(Request $request)
    {
        return ['meta' => ['ISS module api v1']];
    }
}
