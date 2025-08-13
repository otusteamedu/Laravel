<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @title User Registration
 * @description Request for new user registration
 * @group Authentication
 *
 * @bodyParam name string required User's full name. Example: John Doe
 * @bodyParam email string required User's email address. Example: user@example.com
 * @bodyParam password string required User's password. Example: secret123
 * @bodyParam password_confirmation string required Password confirmation. Must match password. Example: secret123
 *
 * @response 201 {
 *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
 *   "name": "John Doe"
 * }
 * @response 422 {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "email": ["The email has already been taken."],
 *     "password": ["The password must be at least 8 characters."]
 *   }
 * }
 */
class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
