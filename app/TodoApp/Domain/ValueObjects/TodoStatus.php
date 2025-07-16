<?php

namespace App\TodoApp\Domain\ValueObjects;

final class TodoStatus
{
    private ModelId $projectId;
    private string $name;
    private int $sort;
    private Color $color;

    public function __construct(
        ModelId $projectId,
        string  $name,
        int     $sort,
        Color  $color,
    ) {
        $this->assertNameIsValid($name);

        $this->projectId = $projectId;
        $this->name = $name;
        $this->sort = $sort;
        $this->color = $color;
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

    private function assertNameIsValid($value)
    {
        if (strlen($value) < 3 || strlen($value) > 64) {
            throw new \InvalidArgumentException('Имя статуса должо быть содержать от 3-х до 64-х символов');
        }
    }
}
