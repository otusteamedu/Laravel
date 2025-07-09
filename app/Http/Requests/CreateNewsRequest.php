<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'content' => ['required', 'string', 'min:2'],
            'author_id' => ['integer'],
            'category_id' => ['required', 'integer'],
            'is_draft' => ['boolean'],
            'published_at' => [  'date', 'after_or_equal:today'],
        ];
    }
}
