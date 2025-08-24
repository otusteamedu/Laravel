<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSheduleRequest extends FormRequest
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
            'language_code' => ['required', 'numeric', 'min:1', 'max:3'],
            'group_code' => ['required', 'numeric', 'min:1', 'max:3'],
            'date' => ['required', 'min:5'],
            'teacher' => ['required', 'min:5'],
        ];
    }
}
