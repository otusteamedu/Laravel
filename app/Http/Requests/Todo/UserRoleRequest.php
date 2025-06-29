<?php

namespace App\Http\Requests\Todo;

use App\Models\TodoRoleEnum;
use Illuminate\Validation\Rule;
use Illuminate\Auth\AuthManager;
use App\Rules\Project\IsProjectMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserRoleRequest extends FormRequest
{
    private ?int $projectId;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(
        AuthManager $auth,
    ): bool {
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
            'projectId' => ['required', 'exists:projects,id'],
            'todoId'  => ['required', 'exists:todos,id,project_id,' . $this->projectId],
            'userId' => ['required', 'exists:users,id', new IsProjectMember()],
            'role' => ['required', Rule::enum(TodoRoleEnum::class)],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'projectId.required' => 'Поле обящательно',
            'projectId.exists'   => 'Проект не существует',
            'todoId.required' => 'Поле обязательно',
            'todoId.exists' => 'Задача не существует',
            'userId.required' => 'Поле обязательно',
            'userId.exists' => 'Пользователь не существует',
            'role.required' => 'Поле обязательно',
            'role.enum' => 'Неверное значение поля',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->projectId = intval(request()->route('projectId'));
        $todoId    = intval(request()->route('todoId'));

        $this->merge([
            'projectId' => $this->projectId,
            'todoId' => $todoId,
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 400));
    }
}
