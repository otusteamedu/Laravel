<?php

namespace App\Services\TeamPlayer;

final class PlayerData
{

    public ?int $id;
    public string $nickname;
    public string $name;
    public string $position;
    public int $team_id;
    public int $price;
    public ?string $avatar_path;


    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->nickname = $data['nickname'];
        $this->name = $data['name'];
        $this->position = $data['position'];
        $this->team_id = $data['team_id'];
        $this->price = $data['price'] ?? 0;
        $this->avatar_path = $data['avatar_path'] ?? null;

    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'name' => $this->name,
            'position' => $this->position,
            'team_id' => $this->team_id,
            'price' => $this->price,
            'avatar_path' => $this->avatar_path,
        ];
    }
}
