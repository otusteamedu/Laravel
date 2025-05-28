<?php

namespace App\Http\Requests\TodoStatus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'sort' => ['required', 'numeric'],
            'color' => ['required', 'hex_color'],
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'project_id.exists' => 'Проект не существует',
            'name.required' => 'Пожалуйста, укажите название статуса.',
            'name.string' => 'Название статуса должно быть строкой.',
            'name.min' => 'Название статуса слишком короткое.',
            'name.max' => 'Название статуса слишком длинное.',
            'sort.required' => 'Пожалуйста, укажите порядок сортировки статуса.',
            'sort.numeric' => 'Порядок сортировкистатуса долкн быть цеклым числом.',
            'color.required' => 'Пожалуйста, укажите цвет для выделения статуса.',
            'color.hex_color' => 'Цвет должен быть строкой в hex формате.',
        ];
    }
}
