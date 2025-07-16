<?php

namespace App\TodoApp\Domain\Models;

use App\TodoApp\Domain\ValueObjects\Color;
use App\TodoApp\Domain\ValueObjects\ModelId;
use App\TodoApp\Domain\ValueObjects\TodoStatus as TodoStatusVO;

final class TodoStatus
{
    private ModelId $id;
    private ModelId $projectId;
    private string $name;
    private int $sort;
    private Color $color;

    public function __construct(
        ModelId $id,
        TodoStatusVO $todoStatus,
    ) {
        $this->id = $id;
        $this->projectId = $todoStatus->getProjectId();
        $this->name = $todoStatus->getName();
        $this->sort = $todoStatus->getSort();
        $this->color = $todoStatus->getColor();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of projectId
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get the value of color
     */
    public function getColor()
    {
        return $this->color;
    }
}
