<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'sort'      => ['integer', 'gte:0'],
        ];
    }

    /**
     * Get custom error messages for validator
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Название категории обязательно для заполнения',
            'name.max'      => 'Название категории не должно превышать 255 символов',
        ];
    }
}
