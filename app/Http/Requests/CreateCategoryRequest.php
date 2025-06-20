<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', 'max:7'],
            'description' => ['nullable', 'string'],
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
            'name.max' => 'Название категории не должно превышать 255 символов',
            'name.unique' => 'Категория с таким названием уже существует',
            'color.required' => 'Цвет категории обязателен для выбора',
            'color.regex' => 'Неверный формат цвета. Используйте формат #RRGGBB или #RGB',
        ];
    }
}
