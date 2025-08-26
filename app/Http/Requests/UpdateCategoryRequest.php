<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('categoryId');

        return [
            'name'      => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'is_active' => ['boolean'],
            'sort'      => ['integer', 'gte:0'],
        ];
    }
}
