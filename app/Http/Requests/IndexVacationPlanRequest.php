<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexVacationPlanRequest extends FormRequest
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
            'per_page' => 'integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'The per_page field must be an integer.',
            'per_page.min' => 'The per_page field must be at least 1.',
            'per_page.max' => 'The per_page field must not be greater than 100.',
        ];
    }
}
