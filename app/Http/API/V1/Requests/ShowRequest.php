<?php

namespace App\Http\API\V1\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\API\V1\Responses\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowRequest extends FormRequest
{
    private $projectId;
    private $statusId;

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
        $this->statusId = request()->route('statusId');

        $this->merge([
            'project_id' => $this->projectId,
            'status_id' => $this->statusId
        ]);
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            new JsonResponse(
                new ErrorResponse(
                    message: 'Ошибка валидации данных запроса',
                    errors: $validator->errors()->getMessages(),
                    code: 400,
                )
            )
        );
    }
}
