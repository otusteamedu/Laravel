<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:новая,в работе,выполнена,отменена'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'priority_id' => ['sometimes', 'exists:priorities,id'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'executor_id' => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'Название задачи не должно превышать 255 символов',
            'status.in' => 'Недопустимый статус задачи',
            'due_date.date' => 'Дата выполнения должна быть валидной датой',
            'priority_id.exists' => 'Выбранный приоритет не существует',
            'category_id.exists' => 'Выбранная категория не существует',
            'executor_id.exists' => 'Выбранный исполнитель не существует',
        ];
    }
}


