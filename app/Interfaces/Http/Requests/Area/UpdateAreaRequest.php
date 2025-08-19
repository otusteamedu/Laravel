<?php

namespace App\Interfaces\Http\Requests\Area;

use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Обработка ошибки валидации.
     *
     * Этот метод вызывается автоматически при провале валидации запроса.
     * Он возвращает JSON-ответ с использованием структуры WebResponse.
     * @param \Illuminate\Contracts\Validation\Validator $validator Валидатор с ошибками валидации
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $response = new WebResponse(
            success: false,
            data: null,
            message: $validator->errors()->first(),
            errors: $validator->errors()->toArray(),
            statusCode: 422
        );

        throw new HttpResponseException(
            response()->json($response->toArray(), 422)
        );
    }

    /**
     * Правила валидации.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name-area' => 'required|string|min:2|max:255',
        ];
    }

    /**
     * Пользовательские сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'name-area.required' => 'Поле ":attribute" обязательно для заполнения.',
            'name-area.string'   => 'Поле ":attribute" должно быть строкой.',
            'name-area.min'      => 'Поле ":attribute" должно содержать не менее :min символов.',
            'name-area.max'      => 'Поле ":attribute" не должно превышать :max символов.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name-area' => 'Название территории',
        ];
    }
}
