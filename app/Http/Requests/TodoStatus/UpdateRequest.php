<?php

namespace App\Http\Requests\TodoStatus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
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
            'status_id' => ['required', 'exists:todo_statuses,id'],
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
            'status_id.exists' => 'Статус не существует',
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
