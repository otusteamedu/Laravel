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
    private string $title;

    private string $preview;

    private string $text;

    private $created_at;

    private $updated_at;

    protected $fillable = ['title', 'preview', 'text', 'created_at', 'updated_at'];

    private function validate(string $title, string $preview, string $text): void
    {
        if (empty($title) or
            empty($preview) or
            empty($text) or
            strlen($title) < 10 or
            strlen($text) < 10 or
            strlen($preview) > 255
        ) {
            throw new InvalidArgumentException('Некорректный набор данных: ожидаются корректно заполенные поля.');
        }
    }

    public function __construct(array $attributes = [])
    {
        echo 'dump blog 1'.PHP_EOL;
        dump($attributes);

        // parent::__construct();

        // if (count($attributes) >= 3) {
        // exit();
        try {
            echo 'dump blog 2'.PHP_EOL;
            dump($attributes['title']);

            $this->validate($attributes['title'], $attributes['preview'], $attributes['text']);
        } catch (InvalidArgumentException $e) {

        }
        $this->title = $attributes['title'];
        $this->preview = $attributes['preview'];
        $this->text = $attributes['text'];
        $this->created_at = now();
        $this->updated_at = now();

        echo 'dump blog 3'.PHP_EOL;
        dump($this->title);
        // }

    }

    /*
        public function fillBlog(string $title, string $preview, string $text): void
        {

            if ($this->validate(['title' => $title, 'preview' => $preview, 'text' => $text])) {
                $this->title = $title;
                $this->preview = $preview;
                $this->text = $text;
            }
        }
    */
}
