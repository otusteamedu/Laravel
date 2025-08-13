<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/*
    @property string $title
    @property string $preview
    @property string $text
    @property timestamp $created_at
    @property timestamp $updated_at
*/

class Blog extends Model
{
    private function validate(string $title, string $preview, string $text): void
    {

        if (empty($title) or
            empty($preview) or
            empty($text) or
            strlen($title) < 10 or
            strlen($text) < 10 or
            strlen($preview) > 255
        ) {
            throw new InvalidArgumentException('Некорректный набор данных: ожидаются корректно заполненные поля.');
        }
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct();

        if (count($attributes) >= 3) {
            try {
                $this->validate($attributes['title'], $attributes['preview'], $attributes['text']);
            } catch (InvalidArgumentException $e) {
                throw $e;
            }
            $this->title = $attributes['title'];
            $this->preview = $attributes['preview'];
            $this->text = $attributes['text'];
            $this->created_at = now();
            $this->updated_at = now();

        }

    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTitle(string $value)
    {
        $this->title = $value;
    }

    public function setPreview(string $value)
    {
        $this->preview = $value;
    }

    public function setText(string $value)
    {
        $this->text = $value;
    }

    public function mySave(array $options = [])
    {
        return $this->save();
    }

    public static function myAll(array $options = [])
    {
        return parent::all();
    }
}
