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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'text' => ['required', 'string', 'min:2'],
            'user_id' => ['integer'],
            'preview' => ['required', 'string', 'min:2', 'max:255'],
            'link' => ['required', 'string', 'min:2', 'max:255'],
            'photo' => ['required', 'string', 'min:2', 'max:255'],
            'published_at' => [  'date', 'after_or_equal:today'],
        ];
    }
}
