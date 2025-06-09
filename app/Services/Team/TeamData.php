<?php

namespace App\Services\Team;

use App\Http\Requests\TeamRequest;

final class TeamData
{
    public ?int $id;
    public string $name;
    public string $nickname;
    public ?string $logo_path;

    public function __construct(
        array $array,
    )
    {
        $this->id = $array['id'] ?? null;
        $this->name = $array['name'];
        $this->nickname = $array['nickname'];
        $this->logo_path = $array['logo_path'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'logo_path' => $this->logo_path,
        ];
    }
}
