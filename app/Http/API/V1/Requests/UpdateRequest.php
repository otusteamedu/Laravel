<?php

namespace App\Http\API\V1\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\API\V1\Responses\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
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
