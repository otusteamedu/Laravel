<?php

namespace App\Interfaces\Http\Requests\Home;

use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetRecipeRequest extends FormRequest
{
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

    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_value' => 'required|numeric|min:1',
            'portions' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'Необходимо добавить хотя бы один продукт.',
            'products.*.product_id.required' => 'Выберите продукт.',
            'products.*.product_id.exists' => 'Продукт не найден.',
            'products.*.product_value.required' => 'Укажите количество продукта.',
            'products.*.product_value.numeric' => 'Количество должно быть числом.',
            'portions.required' => 'Укажите количество порций.',
            'portions.integer' => 'Количество порций должно быть целым числом.',
            'portions.min' => 'Количество порций должно быть не меньше 1.',
        ];
    }
}
