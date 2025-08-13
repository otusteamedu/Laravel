<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @title User Login
 * @description Request for user authentication
 * @group Authentication
 *
 * @bodyParam email string required User's email address. Example: user@example.com
 * @bodyParam password string required User's password. Example: secret123
 *
 * @response 200 {
 *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
 *   "name": "John Doe"
 * }
 * @response 401 {
 *   "message": "Unauthorized"
 * }
 */
class LoginRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
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
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ];
    }
}
