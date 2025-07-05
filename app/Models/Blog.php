<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/*
    @property string $title
    @property string $preview
    @property string $text
    @property string $created_at
    @property string $updated_at
*/

class Blog extends Model
{
    protected $fillable = [
        'title',
        'preview',
        'text',
        // 'created_at',
        // 'updated_at',
    ];

    private function validate($fields): bool
    {
        $validator = Validator::make($fields, [
            'title' => ['required', 'min:10', 'max: 255'],
            'preview' => ['min:10'],
            'text' => ['required', 'min:10'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function fillBlog($title, $preview, $text)
    {

        if ($this->validate(['title' => $title, 'preview' => $preview, 'text' => $text])) {
            $this->title = $title;
            $this->preview = $preview;
            $this->text = $text;
        }
    }
}
