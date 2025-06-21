<?php

namespace App\Http\Requests\Todo;

use Illuminate\Validation\Rule;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Query\Builder;
use Vhar\EmbedVideo\Rules\EmbedVideoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    private int $projectId;

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
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'project_id' => ['required', 'exists:projects,id'],
            'author_id' => ['required', 'exists:users,id'],
            'status_id'  => ['required', 'exists:todo_statuses,id,project_id,' . $this->projectId],
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
            'title.required' => 'Пожалуйста, укажите название задачи.',
            'title.string' => 'Название задачи должно быть строкой.',
            'title.min' => 'Название задачи слишком короткое.',
            'title.max' => 'Название задачи слишком длинное.',
            'project_id.exists' => 'Проект не существует',
            'author_id.exists' => 'Пользователь не существует',
            'status_id.required' => 'Пожалуйста, укажите статус задачи.',
            'status_id.exists' => 'Выбран недоступный для задачи статус.',
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

        $this->merge([
            'project_id' => $this->projectId,
            'author_id' => $this->user()->id
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(redirect()->back()
            ->withInput()
            ->withErrors($validator->errors())
            ->with('error', 'Ошибка валидации формы'));
    }
}
