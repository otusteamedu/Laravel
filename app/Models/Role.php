<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    protected $fillable = [
        'slug',
        'name',
    ];

    /** @var bool  */
    public $timestamps = false;


    protected $columnMap = [
        'id' => 'id',
        'name' => 'name',
        'slug' => 'slug',
    ];

    public function getColumnName($property)
    {
        return $this->columnMap[$property] ?? $property;
    }

    public function getId(): int
    {
        return $this->{$this->getColumnName('id')};
    }

    public function getSlug(): string
    {
        return $this->{$this->getColumnName('slug')};
    }

    public function getName(): string
    {
        return $this->{$this->getColumnName('name')};
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
