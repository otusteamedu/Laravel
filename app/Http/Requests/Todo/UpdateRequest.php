<?php

namespace App\Http\Requests\Todo;

use Illuminate\Auth\AuthManager;
use Vhar\EmbedVideo\Rules\EmbedVideoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    private ?int $projectId;

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
            'todo_id'  => ['required', 'exists:todos,id,project_id,' . $this->projectId],
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after_or_equal:now'],
            'options' => ['nullable', 'array'],
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'todo_id.exists' => 'Задача не существует',
            'project_id.exists' => 'Проект не существует',
            'title.required' => 'Пожалуйста, укажите название задачи.',
            'title.string' => 'Название задачи должно быть строкой.',
            'title.min' => 'Название задачи слишком короткое.',
            'title.max' => 'Название задачи слишком длинное.',
            'description.required' => 'Пожалуйста, заполните описание задачи.',
            'description.string' => 'Описание задачи должно быть текстом.',
            'deadline.required' => 'Пожалуйста, укажите крайний срок выполнения задачи.',
            'deadline.date' => 'Крайний срок выполнения задачи должен быть датой.',
            'deadline.after_or_equal' => 'Крайний срок выполнения задачи должен быть добльше или равен текущей датe.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->projectId = request()->route('projectId');
        $todoId = request()->route('todoId');

        $this->merge([
            'project_id' => $this->projectId,
            'todo_id' => $todoId,
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        debugbar()->info($validator->errors());
        throw new HttpResponseException(redirect()->back()
            ->withInput()
            ->withErrors($validator->errors())
            ->with('error', 'Ошибка валидации формы'));
    }
}
