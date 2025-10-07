<?php

namespace Leobsst\LaravelCmsCore\Http\Requests\Api\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Leobsst\LaravelCmsCore\Concerns\Request\ApiRequestFaillableTrait;

final class GetUserRequest extends FormRequest
{
    use ApiRequestFaillableTrait;

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
            'user' => ['nullable', 'email', 'exists:user_emails,email'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user.email' => 'The user must be a valid email address.',
            'user.exists' => 'The user is not found',
        ];
    }
}
