<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void {
        $slug = $this->slugUnique(
            str($this->{$this->slugFrom()})
                ->slug()
                ->value()
        );

        $this->{$this->slugColumn()} = $this->{$this->slugColumn()} ?? $slug;
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    /**
     * @return string
     */
    protected function slugFrom(): string
    {
        return 'title';
    }

    protected function slugUnique(string $slug): string {
        $originalSlug = $slug;
        $i = 0;

        while($this->isSlugExists($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;
        }

        return $slug;
    }

    protected function isSlugExists(string $slug): bool {
        $query = $this->newQuery()
                      ->where(self::slugColumn(), $slug)
                      ->where($this->getKeyName(), '!=', $this->getKey())
                      ->withoutGlobalScopes();

        return $query->exists();
    }
}
