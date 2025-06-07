<?php

namespace App\Services\Team;

use App\Http\Requests\TeamRequest;

class TeamData
{
    protected ?int $id = null;
    protected string $name;
    protected string $nickname;
    protected ?string $logo_path;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getLogoPath(): ?string
    {
        return $this->logo_path;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
