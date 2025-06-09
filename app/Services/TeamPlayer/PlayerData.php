<?php

namespace App\Services\TeamPlayer;

class PlayerData
{

    private ?int $id;
    private string $nickname;
    private string $name;
    private string $position;
    private int $team_id;
    private int $price;
    private ?string $avatar_path;


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getTeamId(): int
    {
        return $this->team_id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatar_path;
    }

}
