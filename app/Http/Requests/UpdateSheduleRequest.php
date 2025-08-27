<?php

namespace App\Http\Requests;

use App\Config;
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
            'language_code' => ['required', 'numeric', 'min:1', 'max:'.count(Config::LANG)],
            'group_code' => ['required', 'numeric', 'min:1', 'max:'.count(Config::AGE)],
            'date' => ['required', 'min:5'],
            'teacher' => ['required', 'min:5'],
        ];
    }
}
