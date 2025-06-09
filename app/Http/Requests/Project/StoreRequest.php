<?php

namespace App\Http\Requests\Project;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(AuthManager $auth): bool
    {
        return $auth->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id']
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Пожалуйста, укажите название проекта.',
            'name.string' => 'Название проекта должно быть строкой.',
            'name.min' => 'Название проекта слишком короткое.',
            'name.max' => 'Название проекта слишком длинное.',
            'description.string' => 'Описание проекта должно быть строкой.',
            'user_id.required' => 'Не указан администратор проекта.',
            'user_id.exists' => 'Указанный пользователь не найден.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['user_id' => $this->user()->id]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(redirect()
            ->route('projects.create')
            ->withInput()
            ->withErrors($validator->errors())
            ->with('error', 'Ошибка валидации формы'));
    }
}
