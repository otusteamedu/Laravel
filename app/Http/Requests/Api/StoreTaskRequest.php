<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:новая,в работе,выполнена,отменена'],
            'due_date' => ['nullable', 'date', 'after:today'],
            'priority_id' => ['required', 'exists:priorities,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'executor_id' => ['required', 'exists:users,id'],
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
            'title.required' => 'Название задачи обязательно для заполнения',
            'title.max' => 'Название задачи не должно превышать 255 символов',
            'status.in' => 'Недопустимый статус задачи',
            'due_date.date' => 'Дата выполнения должна быть валидной датой',
            'due_date.after' => 'Дата выполнения должна быть в будущем',
            'priority_id.required' => 'Приоритет обязателен для выбора',
            'priority_id.exists' => 'Выбранный приоритет не существует',
            'category_id.required' => 'Категория обязательна для выбора',
            'category_id.exists' => 'Выбранная категория не существует',
            'executor_id.exists' => 'Выбранный исполнитель не существует',
        ];
    }
}


