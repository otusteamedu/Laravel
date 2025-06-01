<?php

namespace App\Http\Requests\TodoStatus;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DestroyRequest extends FormRequest
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
            'project_id' => ['required', 'exists:projects,id'],
            'status_id'  => ['required', 'exists:todo_statuses,id,project_id,' . $this->projectId],
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'project_id.exists' => 'Проект не существует',
            'status_id.exists' => 'Статус не существует',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->projectId = request()->route('projectId');
        $this->merge(['project_id' => $this->projectId]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(redirect()
            ->withInput()
            ->withErrors($validator->errors())
            ->back()->with('error', 'Ошибка валидации формы'));
    }
}
