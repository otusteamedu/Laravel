<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => ['required','string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'category_id' => ['required', 'integer']
        ];
    }
}
