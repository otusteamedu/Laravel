<?php

namespace App\TodoApp\Domain\Models;

use DateTime;
use App\TodoApp\Domain\ValueObjects\ModelId;
use App\TodoApp\Domain\ValueObjects\ProjectName;
use App\TodoApp\Domain\ValueObjects\ProjectUser;
use App\TodoApp\Domain\ValueObjects\ProjectDescription;

final class Project
{
    private ModelId $id;
    private ProjectName $name;
    private ProjectDescription $description;
    private DateTime $created;
    private ?DateTime $updated;
    /** @var ProjectUser[] */
    private array $projectUsers = [];

    public function __construct(
        ModelId $id,
        ProjectName $name,
        ProjectDescription $description,
        DateTime $created,
        ?DateTime $updated = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * Get the value of id
     */
    public function getId(): ModelId
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName(): ProjectName
    {
        return $this->name;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): ProjectDescription
    {
        return $this->description;
    }

    /**
     * Get the value of created
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Get the value of updated
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * Get the value of projectUsers
     * @return ProjectUser[]
     */
    public function getProjectUsers(): array
    {
        return $this->projectUsers;
    }

    public function inviteUser(ProjectUser $user)
    {
        $this->projectUsers[] = $user;
    }

    public function joinUser()
    {
        //
    }

    public function leftUser()
    {
        //        
    }
}
